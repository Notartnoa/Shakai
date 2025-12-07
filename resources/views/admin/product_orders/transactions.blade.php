<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">
            My Transactions
        </h2>
    </x-slot>

    {{-- Stats Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $totalTransactions = $my_transactions->count();
            $paidTransactions = $my_transactions->where('is_paid', true)->count();
            $pendingTransactions = $my_transactions->where('is_paid', false)->count();
            $totalSpent = $my_transactions->where('is_paid', true)->sum('total_price');
        @endphp

        <div class="bg-[#1E1E1E] rounded-xl p-4 border border-[#2A2A2A]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#2D68F8]/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#2D68F8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-[#A0A0A0]">Total Purchases</p>
                    <p class="text-xl font-bold text-white">{{ $totalTransactions }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#1E1E1E] rounded-xl p-4 border border-[#2A2A2A]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-[#A0A0A0]">Completed</p>
                    <p class="text-xl font-bold text-emerald-500">{{ $paidTransactions }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#1E1E1E] rounded-xl p-4 border border-[#2A2A2A]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-[#A0A0A0]">Pending</p>
                    <p class="text-xl font-bold text-amber-500">{{ $pendingTransactions }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#1E1E1E] rounded-xl p-4 border border-[#2A2A2A]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-[#A0A0A0]">Total Spent</p>
                    <p class="text-lg font-bold text-purple-500">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Success/Info Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-emerald-400 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 p-4 bg-[#2D68F8]/10 border border-[#2D68F8]/30 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-[#2D68F8] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-[#2D68F8] font-medium">{{ session('info') }}</p>
            </div>
        </div>
    @endif

    {{-- Transactions List --}}
    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A]">
        {{-- Table Header --}}
        <div class="hidden md:grid md:grid-cols-12 gap-4 p-4 border-b border-[#2A2A2A] text-sm font-medium text-[#A0A0A0]">
            <div class="col-span-4">Product</div>
            <div class="col-span-2">Seller</div>
            <div class="col-span-2 text-right">Amount</div>
            <div class="col-span-1 text-center">Status</div>
            <div class="col-span-1 text-center">Date</div>
            <div class="col-span-2 text-center">Action</div>
        </div>

        @forelse ($my_transactions as $transaction)
            <div class="flex flex-col md:grid md:grid-cols-12 gap-4 p-4 {{ !$loop->last ? 'border-b border-[#2A2A2A]' : '' }} hover:bg-[#2A2A2A]/50 transition-colors">
                {{-- Product --}}
                <div class="col-span-4 flex items-center gap-3">
                    <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-[#2A2A2A] ring-2 ring-[#3A3A3A]">
                        <img src="{{ Storage::url($transaction->Product->cover) }}"
                             class="w-full h-full object-cover"
                             alt="{{ $transaction->Product->name }}">
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-semibold text-white truncate">
                            {{ $transaction->Product->name }}
                        </h3>
                        <p class="text-xs text-[#595959] bg-[#2A2A2A] px-2 py-0.5 rounded-md inline-block mt-1">
                            {{ $transaction->Product->Category->name }}
                        </p>
                    </div>
                </div>

                {{-- Seller --}}
                <div class="col-span-2 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 bg-[#2A2A2A]">
                        @if($transaction->Creator->avatar)
                            <img src="{{ Storage::url($transaction->Creator->avatar) }}"
                                 class="w-full h-full object-cover"
                                 alt="{{ $transaction->Creator->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-[#2D68F8]/20">
                                <span class="text-xs font-bold text-[#2D68F8]">{{ substr($transaction->Creator->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <p class="text-sm text-[#A0A0A0] truncate">{{ $transaction->Creator->name }}</p>
                </div>

                {{-- Amount --}}
                <div class="col-span-2 flex items-center justify-end">
                    <p class="text-sm font-bold text-white">
                        Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Status --}}
                <div class="col-span-1 flex items-center justify-center">
                    @if ($transaction->is_paid)
                        <span class="inline-flex items-center gap-1 py-1.5 px-2.5 rounded-full bg-emerald-500/20 text-emerald-500 font-semibold text-xs">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            PAID
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 py-1.5 px-2.5 rounded-full bg-amber-500/20 text-amber-500 font-semibold text-xs">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            PENDING
                        </span>
                    @endif
                </div>

                {{-- Date --}}
                <div class="col-span-1 flex items-center justify-center">
                    <div class="text-center">
                        <p class="text-xs font-medium text-white">{{ $transaction->created_at->format('d M') }}</p>
                        <p class="text-xs text-[#595959]">{{ $transaction->created_at->format('H:i') }}</p>
                    </div>
                </div>

                {{-- Action --}}
                <div class="col-span-2 flex items-center justify-center">
                    @if ($transaction->is_paid)
                        <a href="{{ $transaction->Product->file_url }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold text-xs hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-sm hover:shadow-emerald-500/25">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                    @else
                        <button type="button"
                                onclick="payNow('{{ $transaction->snap_token }}', '{{ $transaction->midtrans_order_id }}', '{{ route('front.checkout', $transaction->Product->slug) }}')"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold text-xs hover:from-amber-600 hover:to-orange-600 transition-all shadow-sm hover:shadow-amber-500/25">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Pay Now
                        </button>
                    @endif
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#2A2A2A] flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#595959]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">No Transactions Yet</h3>
                <p class="text-[#A0A0A0] text-sm mb-6">You haven't purchased any products yet.</p>
                <a href="{{ route('front.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2D68F8] hover:bg-[#083297] text-white font-semibold text-sm rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Browse Products
                </a>
            </div>
        @endforelse
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
