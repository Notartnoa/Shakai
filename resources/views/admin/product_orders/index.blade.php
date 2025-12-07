<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">
            My Orders
        </h2>
    </x-slot>

    {{-- Stats Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $totalOrders = $my_orders->count();
            $paidOrders = $my_orders->where('is_paid', true)->count();
            $pendingOrders = $my_orders->where('is_paid', false)->count();
            $totalRevenue = $my_orders->where('is_paid', true)->sum('total_price');
        @endphp

        <div class="bg-[#1E1E1E] rounded-xl p-4 border border-[#2A2A2A]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#2D68F8]/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#2D68F8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-[#A0A0A0]">Total Orders</p>
                    <p class="text-xl font-bold text-white">{{ $totalOrders }}</p>
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
                    <p class="text-sm text-[#A0A0A0]">Paid</p>
                    <p class="text-xl font-bold text-emerald-500">{{ $paidOrders }}</p>
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
                    <p class="text-xl font-bold text-amber-500">{{ $pendingOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#1E1E1E] rounded-xl p-4 border border-[#2A2A2A]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-[#A0A0A0]">Revenue</p>
                    <p class="text-lg font-bold text-green-500">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders List --}}
    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A]">
        {{-- Table Header --}}
        <div class="hidden md:grid md:grid-cols-12 gap-4 p-4 border-b border-[#2A2A2A] text-sm font-medium text-[#A0A0A0]">
            <div class="col-span-4">Product</div>
            <div class="col-span-3">Buyer</div>
            <div class="col-span-2 text-right">Amount</div>
            <div class="col-span-2 text-center">Status</div>
            <div class="col-span-1 text-center">Date</div>
        </div>

        @forelse ($my_orders as $order)
            <div class="flex flex-col md:grid md:grid-cols-12 gap-4 p-4 {{ !$loop->last ? 'border-b border-[#2A2A2A]' : '' }} hover:bg-[#2A2A2A]/50 transition-colors">
                {{-- Product --}}
                <div class="col-span-4 flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-[#2A2A2A]">
                        <img src="{{ Storage::url($order->Product->cover) }}"
                             class="w-full h-full object-cover"
                             alt="{{ $order->Product->name }}">
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-semibold text-white truncate">
                            {{ $order->Product->name }}
                        </h3>
                        <p class="text-xs text-[#595959]">
                            {{ $order->Product->Category->name }}
                        </p>
                    </div>
                </div>

                {{-- Buyer --}}
                <div class="col-span-3 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 bg-[#2A2A2A]">
                        @if($order->Buyer->avatar)
                            <img src="{{ Storage::url($order->Buyer->avatar) }}"
                                 class="w-full h-full object-cover"
                                 alt="{{ $order->Buyer->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-[#2D68F8]/20">
                                <span class="text-xs font-bold text-[#2D68F8]">{{ substr($order->Buyer->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $order->Buyer->name }}</p>
                        <p class="text-xs text-[#595959] truncate">{{ $order->Buyer->email }}</p>
                    </div>
                </div>

                {{-- Amount --}}
                <div class="col-span-2 flex items-center justify-end">
                    <p class="text-sm font-bold text-white">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Status --}}
                <div class="col-span-2 flex items-center justify-center">
                    @if ($order->is_paid)
                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full bg-emerald-500/20 text-emerald-500 font-semibold text-xs">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            PAID
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full bg-amber-500/20 text-amber-500 font-semibold text-xs">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            PENDING
                        </span>
                    @endif
                </div>

                {{-- Date --}}
                <div class="col-span-1 flex items-center justify-center">
                    <p class="text-xs text-[#595959]">
                        {{ $order->created_at->format('d M') }}
                    </p>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#2A2A2A] flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#595959]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">No Orders Yet</h3>
                <p class="text-[#A0A0A0] text-sm mb-6">No one has purchased your products yet.</p>
                <a href="{{ route('admin.products.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2D68F8] hover:bg-[#083297] text-white font-semibold text-sm rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Product
                </a>
            </div>
        @endforelse
    </div>
</x-app-layout>
