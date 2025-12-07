{{-- Sidebar Navigation - Dark Theme --}}
<aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-[#1E1E1E] border-r border-[#2A2A2A] z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <div class="flex flex-col h-full">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-[#2A2A2A]">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logos/logo.svg') }}" alt="Sakhai" class="h-8 w-auto">
            </a>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.dashboard')
                         ? 'bg-[#2D68F8] text-white'
                         : 'text-[#A0A0A0] hover:bg-[#2A2A2A] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>

            {{-- My Products --}}
            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.products.*')
                         ? 'bg-[#2D68F8] text-white'
                         : 'text-[#A0A0A0] hover:bg-[#2A2A2A] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                My Products
            </a>

            {{-- My Orders (Sales) --}}
            <a href="{{ route('admin.product_orders.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.product_orders.index')
                         ? 'bg-[#2D68F8] text-white'
                         : 'text-[#A0A0A0] hover:bg-[#2A2A2A] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                My Orders
            </a>

            {{-- My Transactions (Purchases) --}}
            <a href="{{ route('admin.product_orders.transaction') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors
                      {{ request()->routeIs('admin.product_orders.transaction')
                         ? 'bg-[#2D68F8] text-white'
                         : 'text-[#A0A0A0] hover:bg-[#2A2A2A] hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                My Transactions
            </a>

            {{-- Divider --}}
            <div class="my-4 border-t border-[#2A2A2A]"></div>

            {{-- Back to Store --}}
            <a href="{{ route('front.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-[#A0A0A0] hover:bg-[#2A2A2A] hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Back to Store
            </a>
        </nav>

        {{-- User Info at Bottom --}}
        <div class="p-4 border-t border-[#2A2A2A]">
            <div class="flex items-center gap-3 px-3 py-2">
                <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('images/default-avatar.png') }}"
                     class="w-10 h-10 rounded-full object-cover border-2 border-[#414141]"
                     alt="{{ Auth::user()->name }}">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-[#A0A0A0] truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>
