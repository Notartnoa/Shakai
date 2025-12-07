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
    /**
     * Set Midtrans configuration
     */
    private function setupMidtrans()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Debug log
        Log::info('Midtrans config', [
            'server_key_set' => !empty(Config::$serverKey),
            'client_key_set' => !empty(Config::$clientKey),
            'is_production' => Config::$isProduction,
        ]);
    }

    /**
     * Show checkout page
     */
    public function checkout(Product $product)
    {
        // Kalau produk gratis, redirect ke download
        if ($product->price == 0) {
            return redirect()->route('front.download', $product->slug);
        }

        return view('front.checkout', [
            'product' => $product,
        ]);
    }

    /**
     * Process checkout and get Snap Token
     */
    public function store(Product $product, Request $request)
    {
        // Setup Midtrans di awal method
        $this->setupMidtrans();

        // Kalau produk gratis, redirect ke download
        if ($product->price == 0) {
            return response()->json([
                'error' => 'This product is free. No payment needed.',
                'redirect' => route('front.download', $product->slug)
            ], 400);
        }

        // Cek apakah user sudah pernah beli dan sudah bayar
        $existingOrder = ProductOrder::where('product_id', $product->id)
            ->where('buyer_id', Auth::id())
            ->where('is_paid', true)
            ->first();

        if ($existingOrder) {
            return response()->json([
                'error' => 'You have already purchased this product.',
                'redirect' => route('admin.product_orders.transaction')
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Generate unique order ID
            $midtransOrderId = 'ORDER-' . time() . '-' . Auth::id() . '-' . $product->id;

            // Create order record
            $order = ProductOrder::create([
                'product_id' => $product->id,
                'buyer_id' => Auth::id(),
                'creator_id' => $product->creator_id,
                'total_price' => $product->price,
                'is_paid' => false,
                'midtrans_order_id' => $midtransOrderId,
            ]);

            Log::info('Order created', ['order_id' => $order->id, 'midtrans_order_id' => $midtransOrderId]);

            // Midtrans transaction details
            $transactionDetails = [
                'order_id' => $midtransOrderId,
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
                    'id' => (string) $product->id,
                    'price' => (int) $product->price,
                    'quantity' => 1,
                    'name' => substr($product->name, 0, 50), // Midtrans limit 50 chars
                ]
            ];

            // Build transaction data
            $transactionData = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
            ];

            Log::info('Requesting snap token', ['transaction_data' => $transactionData]);

            // Get Snap Token
            $snapToken = Snap::getSnapToken($transactionData);

            Log::info('Snap token received', ['snap_token' => $snapToken ? 'SUCCESS' : 'FAILED']);

            // Update order with snap token
            $order->update([
                'snap_token' => $snapToken,
            ]);

            DB::commit();

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order->id,
                'midtrans_order_id' => $midtransOrderId,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Checkout error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'error' => 'Failed to process checkout. Please try again.',
                'debug' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function notification(Request $request)
    {
        $this->setupMidtrans();

        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $orderId = $notification->order_id;
            $paymentType = $notification->payment_type;
            $transactionId = $notification->transaction_id;

            Log::info('Midtrans notification received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $paymentType,
            ]);

            // Find order by midtrans_order_id
            $order = ProductOrder::where('midtrans_order_id', $orderId)->first();

            if (!$order) {
                Log::error('Order not found', ['midtrans_order_id' => $orderId]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Handle transaction status
            $isPaid = false;
            $proofStatus = '';

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $isPaid = true;
                    $proofStatus = 'captured';
                } else {
                    $proofStatus = 'fraud-detected';
                }
            } elseif ($transactionStatus == 'settlement') {
                $isPaid = true;
                $proofStatus = 'settlement';
            } elseif ($transactionStatus == 'pending') {
                $proofStatus = 'pending';
            } elseif ($transactionStatus == 'deny') {
                $proofStatus = 'denied';
            } elseif ($transactionStatus == 'expire') {
                $proofStatus = 'expired';
            } elseif ($transactionStatus == 'cancel') {
                $proofStatus = 'cancelled';
            }

            // Update order
            $order->update([
                'is_paid' => $isPaid,
                'proof' => $proofStatus . '-' . $transactionId,
                'payment_type' => $paymentType,
                'paid_at' => $isPaid ? now() : null,
            ]);

            Log::info('Order updated', [
                'order_id' => $order->id,
                'is_paid' => $isPaid,
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Midtrans notification error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update payment status from frontend callback
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'transaction_status' => 'required|string',
        ]);

        $order = ProductOrder::where('midtrans_order_id', $request->order_id)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // Hanya update jika status adalah success/settlement
        if (in_array($request->transaction_status, ['settlement', 'capture'])) {
            $order->update([
                'is_paid' => true,
                'paid_at' => now(),
                'payment_type' => 'midtrans',
                'proof' => 'paid-via-snap-callback'
            ]);

            return response()->json(['success' => true, 'message' => 'Payment verified']);
        }

        return response()->json(['success' => false, 'message' => 'Payment not successful']);
    }

    /**
     * Handle finish redirect from Midtrans
     */
    public function finish(Request $request)
    {
        $orderId = $request->get('order_id');
        $status = $request->get('status');

        $order = ProductOrder::where('midtrans_order_id', $orderId)->first();

        // Jika status success dari Midtrans Snap, langsung update
        if ($order && $status === 'success' && !$order->is_paid) {
            $order->update([
                'is_paid' => true,
                'paid_at' => now(),
                'payment_type' => 'midtrans',
                'proof' => 'paid-via-snap'
            ]);

            return redirect()->route('admin.product_orders.transaction')
                ->with('success', 'Payment successful! You can now download the product.');
        }

        if ($order && $order->is_paid) {
            return redirect()->route('admin.product_orders.transaction')
                ->with('success', 'Payment successful! You can now download the product.');
        }

        return redirect()->route('admin.product_orders.transaction')
            ->with('info', 'Payment is being processed. Please wait for confirmation.');
    }
}
