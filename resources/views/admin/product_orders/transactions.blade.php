<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">My Transactions</h1>
                <p class="text-gray-500 mt-1">View your purchase history and download your products</p>
            </div>

            {{-- Success/Info Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-blue-700 font-medium">{{ session('info') }}</p>
                    </div>
                </div>
            @endif

            {{-- Transactions List --}}
            <div class="flex flex-col gap-4">
                @forelse ($my_transactions as $transaction)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-5">
                            {{-- Product Image --}}
                            <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0">
                                <img src="{{ Storage::url($transaction->Product->cover) }}"
                                     class="w-full h-full object-cover"
                                     alt="{{ $transaction->Product->name }}">
                            </div>

                            {{-- Product Info --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-gray-900 truncate">
                                    {{ $transaction->Product->name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $transaction->Product->Category->name }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $transaction->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>

                            {{-- Price --}}
                            <div class="text-right flex-shrink-0">
                                <p class="text-lg font-bold text-gray-900">
                                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Status Badge --}}
                            <div class="flex-shrink-0">
                                @if ($transaction->is_paid)
                                    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full bg-green-100 text-green-700 font-semibold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        PAID
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full bg-amber-100 text-amber-700 font-semibold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        PENDING
                                    </span>
                                @endif
                            </div>

                            {{-- Action Button --}}
                            <div class="flex-shrink-0">
                                @if ($transaction->is_paid)
                                    <a href="{{ $transaction->Product->file_url }}"
                                       target="_blank"
                                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold text-sm hover:from-green-600 hover:to-emerald-600 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                @else
                                    {{-- Tombol Pay Now dengan Snap Token --}}
                                    <button type="button"
                                            onclick="payNow('{{ $transaction->snap_token }}', '{{ $transaction->midtrans_order_id }}', '{{ route('front.checkout', $transaction->Product->slug) }}')"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold text-sm hover:from-amber-600 hover:to-orange-600 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        Pay Now
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">No Transactions Yet</h3>
                        <p class="text-gray-500 mb-6 text-sm">You haven't purchased any products yet.</p>
                        <a href="{{ route('front.index') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Browse Products
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Midtrans Snap JS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
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

        function payNow(snapToken, orderId, checkoutUrl) {
            // Cek apakah snap token ada
            if (!snapToken) {
                // Kalau tidak ada token, redirect ke checkout untuk generate baru
                window.location.href = checkoutUrl;
                return;
            }

            // Buka Midtrans Snap dengan token yang sudah ada
            window.snap.pay(snapToken, {
                onSuccess: async function(result) {
                    console.log('Payment success:', result);

                    // Update status ke database
                    await updatePaymentStatus(result.order_id, 'settlement');

                    // Reload halaman untuk update status
                    window.location.reload();
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    // Tetap di halaman ini, user bisa coba lagi nanti
                    alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    // Token mungkin expired, redirect ke checkout untuk generate baru
                    alert('Terjadi kesalahan. Mengalihkan ke halaman checkout...');
                    window.location.href = checkoutUrl;
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    // User menutup popup, tidak perlu action
                }
            });
        }
    </script>
</x-app-layout>
