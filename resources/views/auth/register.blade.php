<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sakhai') }} - Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Fix autofill background */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #2A2A2A inset !important;
            -webkit-text-fill-color: #ffffff !important;
            caret-color: #ffffff !important;
        }
    </style>
</head>

<body class="bg-[#121212] text-white min-h-screen">
    {{-- Navbar --}}
    <x-navbar />

    {{-- Main Content --}}
    <main class="pt-[74px] min-h-screen flex">
        {{-- Left Side - Form --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16 bg-[#121212]">
            <div class="w-full max-w-md">
                {{-- Header --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Create Account</h1>
                    <p class="text-[#A0A0A0]">Join Sakhai and start exploring digital products</p>
                </div>

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-xl">
                        <ul class="list-disc list-inside text-red-400 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Avatar Upload --}}
                    <div>
                        <label class="block text-sm font-medium text-[#A0A0A0] mb-3">Profile Photo</label>
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div id="avatar-preview"
                                    class="w-20 h-20 rounded-full bg-[#2A2A2A] border-2 border-[#414141] flex items-center justify-center overflow-hidden">
                                    <svg class="w-8 h-8 text-[#A0A0A0]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <img id="avatar-img" class="hidden w-full h-full object-cover" alt="Avatar preview">
                                </div>
                            </div>
                            <div class="flex-1">
                                <label for="avatar"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#2A2A2A] hover:bg-[#3A3A3A] border border-[#414141] rounded-lg cursor-pointer transition-colors">
                                    <svg class="w-5 h-5 text-[#A0A0A0]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-white">Upload Photo</span>
                                </label>
                                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden"
                                    onchange="previewAvatar(event)" required>
                                <p class="text-xs text-[#595959] mt-2">JPG, PNG. Max 2MB</p>
                            </div>
                        </div>
                    </div>

                    {{-- Complete Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-[#A0A0A0] mb-2">Complete
                            Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-[#595959]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                autofocus
                                class="w-full pl-12 pr-4 py-3 bg-[#2A2A2A] border border-[#414141] rounded-xl text-white placeholder-[#595959] focus:outline-none focus:border-[#A0A0A0] focus:ring-1 focus:ring-[#A0A0A0] transition-colors"
                                placeholder="Enter your full name">
                        </div>
                    </div>

                    {{-- Occupation --}}
                    <div>
                        <label for="occupation" class="block text-sm font-medium text-[#A0A0A0] mb-2">Occupation</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-[#595959]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="text" id="occupation" name="occupation" value="{{ old('occupation') }}"
                                required
                                class="w-full pl-12 pr-4 py-3 bg-[#2A2A2A] border border-[#414141] rounded-xl text-white placeholder-[#595959] focus:outline-none focus:border-[#A0A0A0] focus:ring-1 focus:ring-[#A0A0A0] transition-colors"
                                placeholder="e.g. UI/UX Designer">
                        </div>
                    </div>

                    {{-- Email Address --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#A0A0A0] mb-2">Email
                            Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-[#595959]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-12 pr-4 py-3 bg-[#2A2A2A] border border-[#414141] rounded-xl text-white placeholder-[#595959] focus:outline-none focus:border-[#A0A0A0] focus:ring-1 focus:ring-[#A0A0A0] transition-colors"
                                placeholder="you@example.com">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-[#A0A0A0] mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-[#595959]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="w-full pl-12 pr-4 py-3 bg-[#2A2A2A] border border-[#414141] rounded-xl text-white placeholder-[#595959] focus:outline-none focus:border-[#A0A0A0] focus:ring-1 focus:ring-[#A0A0A0] transition-colors"
                                placeholder="••••••••">
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-[#A0A0A0] mb-2">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-[#595959]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full pl-12 pr-4 py-3 bg-[#2A2A2A] border border-[#414141] rounded-xl text-white placeholder-[#595959] focus:outline-none focus:border-[#A0A0A0] focus:ring-1 focus:ring-[#A0A0A0] transition-colors"
                                placeholder="••••••••">
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full py-3.5 bg-[#2D68F8] hover:bg-[#083297] active:bg-[#062162] text-white font-semibold rounded-xl transition-colors">
                        Create My Account
                    </button>

                    {{-- Login Link --}}
                    <p class="text-center text-[#A0A0A0] text-sm">
                        Already have an account?
                        <a href="{{ route('login') }}"
                            class="text-[#2D68F8] hover:text-white font-medium transition-colors">
                            Sign In
                        </a>
                    </p>
                </form>
            </div>
        </div>

        {{-- Right Side - Image --}}
        <div class="hidden lg:block lg:w-1/2 relative">
            <div class="absolute inset-0 bg-gradient-to-r from-[#121212] via-[#121212]/50 to-transparent z-10"></div>
            <img src="{{ asset('images/backgrounds/hero-image.png') }}" class="w-full h-full object-cover"
                alt="Register background">
        </div>
    </main>

    {{-- JavaScript --}}
    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const img = document.getElementById('avatar-img');
                    const svg = preview.querySelector('svg');

                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if (svg) svg.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        // Dropdown menu handler
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
</body>

</html>
