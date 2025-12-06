<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
                <p class="text-gray-500 mt-1">Orders from customers who purchased your products</p>
            </div>

            {{-- Orders List --}}
            <div class="flex flex-col gap-4">
                @forelse ($my_orders as $order)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-5">
                            {{-- Product Image --}}
                            <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0">
                                <img src="{{ Storage::url($order->Product->cover) }}"
                                     class="w-full h-full object-cover"
                                     alt="{{ $order->Product->name }}">
                            </div>

                            {{-- Product Info --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-gray-900 truncate">
                                    {{ $order->Product->name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Buyer: {{ $order->Buyer->name }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>

                            {{-- Price --}}
                            <div class="text-right flex-shrink-0">
                                <p class="text-lg font-bold text-gray-900">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Status Badge --}}
                            <div class="flex-shrink-0">
                                @if ($order->is_paid)
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
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">No Orders Yet</h3>
                        <p class="text-gray-500 text-sm">No one has purchased your products yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
