@extends('front.layouts.app')
@section('title', 'Sakhai')
@section('content')

    <x-navbar />

    <header class="w-full pt-[74px] pb-[103px] relative z-0">
        <div class="container max-w-[1130px] mx-auto flex flex-col z-10">
            <div class="flex flex-col gap-4 mt-7 z-10">
                <div class="flex items-center gap-3">
                    <p class="bg-[#2A2A2A] font-semibold text-belibang-grey rounded-[4px] p-[8px_16px] w-fit">
                        {{ $product->Category->name }}
                    </p>
                    @if ($product->price == 0)
                        <p class="bg-emerald-500 font-semibold text-white rounded-[4px] p-[8px_16px] w-fit">
                            FREE
                        </p>
                    @endif
                </div>
                <h1 class="font-semibold text-[55px]">{{ $product->name }}</h1>
            </div>
        </div>
        <div class="background-image w-full h-full absolute top-0 overflow-hidden z-0">
            <img src="{{ asset('images/backgrounds/hero.png') }}" class="w-full h-full object-cover" alt="hero image">
        </div>
        <div class="w-full h-1/3 absolute bottom-0 bg-gradient-to-b from-belibang-black/0 to-belibang-black z-0"></div>
        <div class="w-full h-full absolute top-0 bg-belibang-black/95 z-0"></div>
    </header>

    <section id="DetailsContent" class="container max-w-[1130px] mx-auto mb-[32px] relative -top-[70px]">
        <div class="flex flex-col gap-8">
            {{-- THUMBNAIL BESAR --}}
            <div class="w-full h-[700px] flex shrink-0 rounded-[20px] overflow-hidden">
                <img src="{{ Storage::url($product->cover) }}" class="w-full h-full object-cover" alt="hero image">
            </div>

            {{-- GRID DETAIL IMAGES --}}
            @if ($product->detail_images)
                @php
                    $detailImages = is_array($product->detail_images)
                        ? $product->detail_images
                        : json_decode($product->detail_images, true);
                @endphp

                @if (is_array($detailImages) && count($detailImages) > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @foreach ($detailImages as $imagePath)
                            <div class="w-full h-[400px] rounded-[20px] overflow-hidden">
                                <img src="{{ Storage::url($imagePath) }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                    alt="detail image">
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

            {{-- CONTENT SECTION --}}
            <div class="flex gap-8">
                {{-- LEFT COLUMN: Overview & Format --}}
                <div class="flex flex-col p-[30px] gap-5 bg-[#181818] rounded-[20px] w-[700px] shrink-0 h-fit">
                    {{-- Overview --}}
                    <div class="flex flex-col gap-4">
                        <p class="font-semibold text-xl">Overview</p>
                        <p class="text-belibang-grey leading-[30px]">{{ $product->about }}</p>
                    </div>

                    {{-- Format Section --}}
                    <div class="flex flex-col gap-4">
                        <p class="font-semibold text-xl">Format</p>
                        <div class="flex items-center gap-[10px] flex-wrap">
                            @php
                                $formats = explode(',', $product->file_formats);
                                $formatLogos = [
                                    'figma' => 'figma-logo.svg',
                                    'framer' => 'framer.png',
                                    'illustrator' => 'illustrator.svg',
                                    'laravel' => 'Laravel.svg',
                                    'blender' => 'blender.svg',
                                    'python' => 'Python.svg',
                                    'bootstrap' => 'bootstrap.svg',
                                    'html' => 'html.svg',
                                    'react_js' => 'reactJS.svg',
                                    'vue' => 'Vue.svg',
                                    'golang' => 'golang.svg',
                                    'flutter' => 'flutter.svg',
                                    'excell' => 'Excel.svg',
                                    'kotlin' => 'Kotlin.svg',
                                ];
                            @endphp

                            @foreach ($formats as $format)
                                @if (isset($formatLogos[trim($format)]))
                                    <div
                                        class="w-9 h-9 justify-center items-center rounded-full flex shrink-0 overflow-hidden border-[0.69px] border-[#414141] bg-[#2A2A2A] hover:border-[#B05CB0] transition-all duration-300">
                                        <img src="{{ asset('images/logos/' . $formatLogos[trim($format)]) }}"
                                            class='p-[5px] w-full h-full object-contain' alt="{{ trim($format) }}"
                                            title="{{ ucfirst(str_replace('_', ' ', trim($format))) }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Price & Creator --}}
                <div class="flex flex-col w-[366px] gap-[30px] flex-nowrap overflow-y-visible">
                    {{-- Price Card --}}
                    <div
                        class="p-[2px] {{ $product->price == 0 ? 'bg-gradient-to-r from-emerald-400 to-teal-500' : 'bg-img-purple-to-orange' }} rounded-[20px] flex w-full h-fit sticky top-[90px]">
                        <div class="w-full p-[28px] bg-[#181818] rounded-[20px] flex flex-col gap-[26px]">
                            <div class="flex flex-col gap-3">
                                {{-- Price Display --}}
                                @if ($product->price == 0)
                                    <p
                                        class="font-semibold text-4xl bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-teal-500">
                                        FREE
                                    </p>
                                @else
                                    <p
                                        class="font-semibold text-4xl bg-clip-text text-transparent bg-gradient-to-r from-[#B05CB0] to-[#FCB16B]">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                @endif

                                {{-- Features List --}}
                                <div class="flex flex-col gap-[10px]">
                                    <div class="flex items-center gap-[10px]">
                                        <div class="w-4 h-4 flex shrink-0">
                                            <img src="{{ asset('images/icons/check.svg') }}" alt="icon">
                                        </div>
                                        <p class="text-belibang-grey">100% Original Content</p>
                                    </div>
                                    <div class="flex items-center gap-[10px]">
                                        <div class="w-4 h-4 flex shrink-0">
                                            <img src="{{ asset('images/icons/check.svg') }}" alt="icon">
                                        </div>
                                        <p class="text-belibang-grey">Lifetime Support</p>
                                    </div>
                                    <div class="flex items-center gap-[10px]">
                                        <div class="w-4 h-4 flex shrink-0">
                                            <img src="{{ asset('images/icons/check.svg') }}" alt="icon">
                                        </div>
                                        <p class="text-belibang-grey">High-Performance Code</p>
                                    </div>
                                    <div class="flex items-center gap-[10px]">
                                        <div class="w-4 h-4 flex shrink-0">
                                            <img src="{{ asset('images/icons/check.svg') }}" alt="icon">
                                        </div>
                                        <p class="text-belibang-grey">Customizable Themes</p>
                                    </div>
                                    <div class="flex items-center gap-[10px]">
                                        <div class="w-4 h-4 flex shrink-0">
                                            <img src="{{ asset('images/icons/check.svg') }}" alt="icon">
                                        </div>
                                        <p class="text-belibang-grey">Responsive Design</p>
                                    </div>
                                    <div class="flex items-center gap-[10px]">
                                        <div class="w-4 h-4 flex shrink-0">
                                            <img src="{{ asset('images/icons/check.svg') }}" alt="icon">
                                        </div>
                                        <p class="text-belibang-grey">Comprehensive Documentation</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Button: FREE = Download, PAID = Checkout --}}
                            @if ($product->price == 0)
                                <a href="{{ route('front.download', $product->slug) }}"
                                    class="bg-gradient-to-r from-emerald-500 to-teal-500 text-center font-semibold p-[12px_20px] rounded-full hover:from-emerald-600 hover:to-teal-600 active:from-emerald-700 active:to-teal-700 transition-all duration-300 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download Free
                                </a>
                            @else
                                <a href="{{ route('front.checkout', $product->slug) }}"
                                    class="bg-[#2D68F8] text-center font-semibold p-[12px_20px] rounded-full hover:bg-[#083297] active:bg-[#062162] transition-all duration-300">
                                    Checkout
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="NewProduct" class="container max-w-[1130px] mx-auto mb-[102px] flex flex-col gap-8">
        <h2 class="font-semibold text-[32px]">More Product</h2>
        <div class="grid grid-cols-4 gap-[22px]">
            @forelse ($other_products as $other_product)
                <div class="product-card flex flex-col rounded-[18px] bg-[#181818] overflow-hidden">
                    <a href="{{ route('front.details', $other_product->slug) }}"
                        class="thumbnail w-full h-[180px] flex shrink-0 overflow-hidden relative">
                        <img src="{{ Storage::url($other_product->cover) }}" class="w-full h-full object-cover"
                            alt="thumbnail">

                        @if ($other_product->price == 0)
                            <p
                                class="backdrop-blur bg-emerald-500/90 text-white font-semibold rounded-[4px] p-[4px_10px] absolute top-3 right-[14px] z-10">
                                FREE
                            </p>
                        @else
                            <p class="backdrop-blur bg-black/50 rounded-[4px] p-[4px_8px] absolute top-3 right-[14px] z-10">
                                Rp {{ number_format($other_product->price, 0, ',', '.') }}
                            </p>
                        @endif
                    </a>
                    <div class="p-[10px_14px_12px] h-full flex flex-col justify-between gap-[14px]">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('front.details', $other_product->slug) }}"
                                class="font-semibold line-clamp-2 hover:line-clamp-none">{{ $other_product->name }}</a>
                            <p
                                class="bg-[#2A2A2A] font-semibold text-xs text-belibang-grey rounded-[4px] p-[4px_6px] w-fit">
                                {{ $other_product->Category->name }}
                            </p>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <div class="w-6 h-6 flex shrink-0 items-center justify-center rounded-full overflow-hidden">
                                <img src="{{ Storage::url($other_product->Creator->avatar) }}"
                                    class="w-full h-full object-cover" alt="creator">
                            </div>
                            <a href="" class="font-semibold text-xs text-belibang-grey">
                                {{ $other_product->Creator->name }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-center text-belibang-grey">Belum ada product lain</p>
            @endforelse
        </div>
    </section>

    <x-testimonials />
    <x-footer />

@endsection

@push('after-script')
    <script>
        $('.testi-carousel').flickity({
            cellAlign: 'left',
            contain: true,
            pageDots: false,
            prevNextButtons: false,
        });

        $('.btn-prev').on('click', function() {
            $('.testi-carousel').flickity('previous', true);
        });

        $('.btn-next').on('click', function() {
            $('.testi-carousel').flickity('next', true);
        });
    </script>

    <script>
        const searchInput = document.getElementById('searchInput');
        const resetButton = document.getElementById('resetButton');

        if (searchInput && resetButton) {
            searchInput.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    resetButton.classList.remove('hidden');
                } else {
                    resetButton.classList.add('hidden');
                }
            });

            resetButton.addEventListener('click', function() {
                resetButton.classList.add('hidden');
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuButton = document.getElementById('menu-button');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            if (menuButton && dropdownMenu) {
                menuButton.addEventListener('click', function() {
                    dropdownMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function(event) {
                    const isClickInside = menuButton.contains(event.target) || dropdownMenu.contains(event
                        .target);
                    if (!isClickInside) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
@endpush
