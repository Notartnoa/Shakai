<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Disable SSL verification (development only!)
        Config::$curlOptions = config('midtrans.curl_options');
    }

    public function checkout(Product $product)
    {
        return view('front.checkout', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        DB::beginTransaction();

        try {
            // Create order
            $order = ProductOrder::create([
                'product_id' => $product->id,
                'buyer_id' => Auth::id(),
                'creator_id' => $product->creator_id,
                'total_price' => $product->price,
                'is_paid' => false,
                'proof' => null,
            ]);

            // Midtrans transaction details
            $orderId = 'ORDER-' . $order->id . '-' . time();

            $transactionDetails = [
                'order_id' => $orderId,
                'gross_amount' => (int) $product->price,
            ];

            // Customer details
            $customerDetails = [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ];

            // Item details
            $itemDetails = [
                [
                    'id' => $product->id,
                    'price' => (int) $product->price,
                    'quantity' => 1,
                    'name' => $product->name,
                ]
            ];

            // Midtrans transaction array
            $transactionData = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
            ];

            // Get Snap Token
            $snapToken = Snap::getSnapToken($transactionData);

            // Update order with snap token and order_id
            $order->update([
                'snap_token' => $snapToken,
            ]);

            DB::commit();

            // Log for debugging
            Log::info('Order created', [
                'order_id' => $order->id,
                'midtrans_order_id' => $orderId,
                'snap_token' => $snapToken
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error
            Log::error('Checkout error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification/callback
     * This is called by Midtrans server when payment status changes
     */
    public function notification(Request $request)
    {
        try {
            // Create notification instance
            $notification = new Notification();

            // Get transaction status
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $orderId = $notification->order_id;

            // Extract real order ID from order_id string (ORDER-123-timestamp)
            $orderIdParts = explode('-', $orderId);
            $realOrderId = isset($orderIdParts[1]) ? $orderIdParts[1] : null;

            if (!$realOrderId) {
                Log::error('Invalid order ID format', ['order_id' => $orderId]);
                return response()->json(['status' => 'error', 'message' => 'Invalid order ID'], 400);
            }

            // Find order
            $order = ProductOrder::find($realOrderId);

            if (!$order) {
                Log::error('Order not found', ['order_id' => $realOrderId]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Log notification
            Log::info('Midtrans notification received', [
                'order_id' => $realOrderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            // Handle different transaction statuses
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    // Transaction captured and accepted
                    $order->update([
                        'is_paid' => true,
                        'proof' => 'midtrans-' . $notification->transaction_id,
                    ]);
                }
            } elseif ($transactionStatus == 'settlement') {
                // Transaction successfully settled
                $order->update([
                    'is_paid' => true,
                    'proof' => 'midtrans-' . $notification->transaction_id,
                ]);
            } elseif ($transactionStatus == 'pending') {
                // Transaction is pending
                $order->update([
                    'is_paid' => false,
                    'proof' => 'pending-' . $notification->transaction_id,
                ]);
            } elseif ($transactionStatus == 'deny') {
                // Transaction denied
                $order->update([
                    'is_paid' => false,
                    'proof' => 'denied-' . $notification->transaction_id,
                ]);
            } elseif ($transactionStatus == 'expire') {
                // Transaction expired
                $order->update([
                    'is_paid' => false,
                    'proof' => 'expired-' . $notification->transaction_id,
                ]);
            } elseif ($transactionStatus == 'cancel') {
                // Transaction cancelled
                $order->update([
                    'is_paid' => false,
                    'proof' => 'cancelled-' . $notification->transaction_id,
                ]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Midtrans notification error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
