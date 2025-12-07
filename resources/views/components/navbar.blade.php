<nav class="w-full fixed top-0 bg-[#00000010] backdrop-blur-lg z-10">
    <div class="container max-w-[1130px] mx-auto grid grid-cols-3 items-center h-[74px]">
        <!-- Kolom 1: Logo (kiri) -->
        <div class="flex items-center">
            <a href="{{ route('front.index') }}" class="flex w-[154px] shrink-0 items-center">
                <img src="{{ asset('images/logos/logo.svg') }}" alt="logo">
            </a>
        </div>

        <!-- Kolom 2: Menu (tengah) -->
        <ul class="flex gap-6 items-center justify-center">
            <li class="text-belibang-grey hover:text-belibang-light-grey transition-all duration-300">
                <a href="{{ route('front.index') }}">Home</a>
            </li>
            <li class="text-belibang-grey hover:text-belibang-light-grey transition-all duration-300 relative">
                <button id="menu-button" class="flex items-center gap-1 focus:text-belibang-light-grey">
                    <span>Categories</span>
                    <img src="{{ asset('images/icons/arrow-down.svg') }}" alt="icon">
                </button>
                <div
                    class="dropdown-menu hidden absolute top-[52px] grid grid-cols-2 p-4 gap-[10px] w-[526px] rounded-[20px] bg-[#1E1E1E] border border-[#414141] z-10">
                    <div
                        class="col-span-2 flex justify-between items-center rounded-2xl p-[12px_16px] border border-[#414141] hover:bg-[#2A2A2A] transition-all duration-300">
                        <div class="flex items-center">
                            <a href="" class="w-[58px] h-[58px] flex shrink-0 flex items-center">
                                <img src="{{ asset('images/icons/cart.svg') }}" alt="icon">
                            </a>
                            <a href="" class="flex flex-col">
                                <p class="font-bold text-sm text-white">All Products</p>
                                <p class="text-xs text-belibang-grey">Everything in One Place</p>
                            </a>
                        </div>
                        <div class="w-6 h-6 flex shrink-0">
                            <img src="{{ asset('images/icons/crown.svg') }}" alt="icon">
                        </div>
                    </div>
                    <div
                        class="flex justify-between items-center rounded-2xl p-[12px_16px] border border-[#414141] hover:bg-[#2A2A2A] transition-all duration-300">
                        <div class="flex items-center">
                            <a href="" class="w-[58px] h-[58px] flex shrink-0 flex items-center">
                                <img src="{{ asset('images/ic_template.svg') }}" alt="icon">
                            </a>
                            <a href="" class="flex flex-col">
                                <p class="font-bold text-sm text-white">UI Kit</p>
                                <p class="text-xs text-belibang-grey">Interface essentials</p>
                            </a>
                        </div>
                    </div>
                    <div
                        class="flex justify-between items-center rounded-2xl p-[12px_16px] border border-[#414141] hover:bg-[#2A2A2A] transition-all duration-300">
                        <div class="flex items-center">
                            <a href="" class="w-[58px] h-[58px] flex shrink-0 flex items-center">
                                <img src="{{ asset('images/ic_course.svg') }}" alt="icon">
                            </a>
                            <a href="" class="flex flex-col">
                                <p class="font-bold text-sm text-white">Icon</p>
                                <p class="text-xs text-belibang-grey">Minimal graphic set</p>
                            </a>
                        </div>
                    </div>
                    <div
                        class="flex justify-between items-center rounded-2xl p-[12px_16px] border border-[#414141] hover:bg-[#2A2A2A] transition-all duration-300">
                        <div class="flex items-center">
                            <a href="" class="w-[58px] h-[58px] flex shrink-0 flex items-center">
                                <img src="{{ asset('images/ic_font.svg') }}" alt="icon">
                            </a>
                            <a href="" class="flex flex-col">
                                <p class="font-bold text-sm text-white">Source Code</p>
                                <p class="text-xs text-belibang-grey">Reusable project code</p>
                            </a>
                        </div>
                    </div>
                    <div
                        class="flex justify-between items-center rounded-2xl p-[12px_16px] border border-[#414141] hover:bg-[#2A2A2A] transition-all duration-300">
                        <div class="flex items-center">
                            <a href="" class="w-[58px] h-[58px] flex shrink-0 flex items-center">
                                <img src="{{ asset('images/ic_ebook.svg') }}" alt="icon">
                            </a>
                            <a href="" class="flex flex-col">
                                <p class="font-bold text-sm text-white">Illustration</p>
                                <p class="text-xs text-belibang-grey">Creative visual art</p>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            <li class="text-belibang-grey hover:text-belibang-light-grey transition-all duration-300">
                <a href="">Stories</a>
            </li>
            <li class="text-belibang-grey hover:text-belibang-light-grey transition-all duration-300">
                <a href="">Benefits</a>
            </li>
            <li class="text-belibang-grey hover:text-belibang-light-grey transition-all duration-300">
                <a href="">About</a>
            </li>
        </ul>

        <!-- Kolom 3: Auth Buttons (kanan) -->
        <div class="flex gap-6 items-center justify-end">
            @guest
                <a href="{{ route('login') }}"
                    class="text-belibang-grey hover:text-belibang-light-grey transition-all duration-300">Sign In</a>
                <a href="{{ route('register') }}"
                    class="p-[8px_16px] w-fit h-fit rounded-[12px] text-belibang-grey border border-belibang-dark-grey hover:bg-[#2A2A2A] hover:text-white transition-all duration-300">Sign up</a>
            @endguest

            @auth
                <a href="{{ route('admin.dashboard') }}"
                    class="p-[8px_16px] w-fit h-fit rounded-[12px] text-belibang-grey border border-belibang-dark-grey hover:bg-[#2A2A2A] hover:text-white transition-all duration-300">My Dashboard</a>
            @endauth
        </div>
    </div>
</nav>
