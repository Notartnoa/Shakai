<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-white">
            My Products
        </h2>
    </x-slot>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/50 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-emerald-500 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Products List --}}
    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A]">
        {{-- Header with Add Button --}}
        <div class="flex items-center justify-between p-5 border-b border-[#2A2A2A]">
            <div>
                <h3 class="text-lg font-semibold text-white">All Products</h3>
                <p class="text-sm text-[#595959] mt-0.5">{{ $products->count() }} {{ Str::plural('product', $products->count()) }}</p>
            </div>
            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#2D68F8] hover:bg-[#083297] text-white font-medium rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </a>
        </div>

        {{-- Product Items --}}
        @forelse ($products as $product)
            <div class="flex items-center gap-5 p-5 {{ !$loop->last ? 'border-b border-[#2A2A2A]' : '' }} hover:bg-[#2A2A2A]/50 transition-colors">
                {{-- Product Image --}}
                <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-[#2A2A2A]">
                    <img src="{{ Storage::url($product->cover) }}"
                         class="w-full h-full object-cover"
                         alt="{{ $product->name }}">
                </div>

                {{-- Product Info --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-semibold text-white truncate">
                        {{ $product->name }}
                    </h3>
                    <p class="text-sm text-[#A0A0A0] mt-0.5">
                        {{ $product->category->name }}
                    </p>
                    <div class="flex items-center gap-3 mt-2">
                        @if($product->price == 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-500">
                                FREE
                            </span>
                        @else
                            <span class="text-sm font-semibold text-white">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    {{-- View Button --}}
                    <a href="{{ route('front.details', $product->slug) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-3 py-2 bg-[#2A2A2A] hover:bg-[#363636] text-[#A0A0A0] hover:text-white rounded-lg transition-colors"
                       title="View">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span class="hidden sm:inline text-sm font-medium">View</span>
                    </a>

                    {{-- Edit Button --}}
                    <a href="{{ route('admin.products.edit', $product->id) }}"
                       class="inline-flex items-center gap-2 px-3 py-2 bg-[#2D68F8]/10 hover:bg-[#2D68F8]/20 text-[#2D68F8] rounded-lg transition-colors"
                       title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="hidden sm:inline text-sm font-medium">Edit</span>
                    </a>

                    {{-- Delete Button --}}
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-lg transition-colors"
                                title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span class="hidden sm:inline text-sm font-medium">Delete</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#2A2A2A] flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#595959]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">No Products Yet</h3>
                <p class="text-[#A0A0A0] mb-6">Get started by creating your first product.</p>
                <a href="{{ route('admin.products.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2D68F8] hover:bg-[#083297] text-white font-medium rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Your First Product
                </a>
            </div>
        @endforelse
    </div>
</x-app-layout>
