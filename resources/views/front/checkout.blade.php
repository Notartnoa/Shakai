@extends('front.layouts.app')
@section('title', 'Checkout - ' . $product->name)
@section('content')

    <x-navbar />

    <section id="checkout" class="container max-w-[1130px] mx-auto mt-[30px] mb-[100px]">
        <div class="w-full flex justify-center gap-[60px]">
            {{-- Product Info --}}
            <div class="product-info flex flex-col gap-4 w-[450px] h-fit mt-[18px]">
                <h1 class="font-semibold text-[32px]">Checkout Product</h1>
                <div class="product-detail flex flex-col gap-3">
                    <div class="thumbnail w-full h-[280px] flex shrink-0 rounded-[20px] overflow-hidden">
                        <img src="{{ Storage::url($product->cover) }}" class="w-full h-full object-cover" alt="thumbnail">
                    </div>
                    <div class="product-title flex flex-col gap-[30px]">
                        <div class="flex flex-col gap-3">
                            <p class="font-semibold text-xl">{{ $product->name }}</p>
                            <p
                                class="bg-[#2A2A2A] font-semibold text-xs text-belibang-grey rounded-[4px] p-[4px_6px] w-fit">
                                {{ $product->Category->name }}
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full flex shrink-0 overflow-hidden">
                                    <img src="{{ Storage::url($product->Creator->avatar) }}"
                                        class="w-full h-full object-cover" alt="creator">
                                </div>
                                <p class="font-semibold text-belibang-grey">{{ $product->Creator->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Card --}}
            <div
                class="flex flex-col p-[30px] gap-[30px] rounded-[20px] w-[450px] border-2 border-belibang-darker-grey h-fit">
                <div class="flex flex-col gap-4">
                    <p class="font-semibold text-xl">Order Summary</p>

                    <div class="flex flex-col gap-3">
                        <div class="flex justify-between items-center">
                            <p class="text-belibang-grey">Product Price</p>
                            <p class="font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-belibang-grey">Platform Fee</p>
                            <p class="font-semibold text-emerald-500">FREE</p>
                        </div>
                        <hr class="border-belibang-darker-grey">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold text-lg">Total</p>
                            <p
                                class="font-semibold text-2xl bg-clip-text text-transparent bg-gradient-to-r from-[#B05CB0] to-[#FCB16B]">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <p class="font-semibold text-xl">Payment Method</p>
                    <p class="text-sm text-belibang-grey">
                        Click the button below to proceed with payment. You can pay using various methods including bank
                        transfer, e-wallet, credit card, and more.
                    </p>

                    {{-- Payment logos --}}
                    <div class="flex flex-wrap gap-3 items-center">
                        <div class="p-2 bg-white rounded-lg">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/120px-Logo_dana_blue.svg.png"
                                class="h-5 object-contain" alt="DANA">
                        </div>
                        <div class="p-2 bg-white rounded-lg">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/120px-Logo_ovo_purple.svg.png"
                                class="h-5 object-contain" alt="OVO">
                        </div>
                        <div class="p-2 bg-white rounded-lg">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/120px-Gopay_logo.svg.png"
                                class="h-5 object-contain" alt="GoPay">
                        </div>
                        {{-- <div class="p-2 bg-white rounded-lg">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Shopee_Pay_logo.svg/120px-Shopee_Pay_logo.svg.png" class="h-5 object-contain" alt="ShopeePay">
                        </div> --}}
                        <div class="p-2 bg-white rounded-lg px-3">
                            <span class="text-xs font-semibold text-gray-600">Bank Transfer</span>
                        </div>
                    </div>
                </div>

                {{-- Error message --}}
                <div id="error-message" class="hidden p-4 bg-red-500/10 border border-red-500 rounded-lg">
                    <p class="text-red-500 text-sm" id="error-text"></p>
                </div>

                {{-- Pay button --}}
                <button type="button" id="pay-button"
                    class="rounded-full text-center bg-[#2D68F8] p-[14px_20px] font-semibold hover:bg-[#083297] active:bg-[#062162] transition-all duration-300 flex items-center justify-center gap-2">
                    <span id="button-text">Pay Now</span>
                    <svg id="button-spinner" class="hidden animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>

                <p class="text-xs text-center text-belibang-grey">
                    By clicking "Pay Now", you agree to our Terms of Service and Privacy Policy.
                    Payment is processed securely by Midtrans.
                </p>
            </div>
        </div>
    </section>

    <x-footer />

@endsection

@push('after-script')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        const payButton = document.getElementById('pay-button');
        const buttonText = document.getElementById('button-text');
        const buttonSpinner = document.getElementById('button-spinner');
        const errorMessage = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');

        let currentOrderId = null; // Simpan order_id

        function showLoading() {
            payButton.disabled = true;
            buttonText.textContent = 'Processing...';
            buttonSpinner.classList.remove('hidden');
        }

        function hideLoading() {
            payButton.disabled = false;
            buttonText.textContent = 'Pay Now';
            buttonSpinner.classList.add('hidden');
        }

        function showError(message) {
            errorText.textContent = message;
            errorMessage.classList.remove('hidden');
        }

        function hideError() {
            errorMessage.classList.add('hidden');
        }

        // Function untuk update payment status
        async function updatePaymentStatus(orderId, transactionStatus) {
            try {
                const response = await fetch('{{ route("front.checkout.update-status") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        transaction_status: transactionStatus
                    })
                });
                return await response.json();
            } catch (error) {
                console.error('Update status error:', error);
                return null;
            }
        }

        payButton.addEventListener('click', async function() {
            hideError();
            showLoading();

            try {
                const response = await fetch('{{ route("front.checkout.store", $product->slug) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Failed to process checkout');
                }

                // Simpan order_id untuk dipakai nanti
                currentOrderId = data.midtrans_order_id;

                // Open Midtrans Snap popup
                window.snap.pay(data.snap_token, {
                    onSuccess: async function(result) {
                        console.log('Payment success:', result);
                        showLoading();
                        buttonText.textContent = 'Verifying...';

                        // Update status ke database
                        await updatePaymentStatus(result.order_id, 'settlement');

                        window.location.href = '{{ route("admin.product_orders.transaction") }}?status=success';
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        window.location.href = '{{ route("admin.product_orders.transaction") }}?status=pending';
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        hideLoading();
                        showError('Payment failed. Please try again.');
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        hideLoading();
                    }
                });

            } catch (error) {
                console.error('Checkout error:', error);
                hideLoading();
                showError(error.message || 'Something went wrong. Please try again.');
            }
        });
    </script>
@endpush
