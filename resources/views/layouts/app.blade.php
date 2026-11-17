<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Carvia') }} — Jual Beli Mobil Bekas Terpercaya</title>

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-jakarta antialiased bg-slate-50 text-slate-800">

    {{-- ===================== NAVBAR ===================== --}}
    <nav x-data="{ mobileOpen: false, userOpen: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                    <div class="w-8 h-8 bg-[#0f1e36] rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold text-[#0f1e36] tracking-tight">Carvia<span class="text-blue-500">.id</span></span>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('cars.index') }}"
                       class="text-sm font-medium transition-colors {{ request()->routeIs('cars.index') ? 'text-[#0f1e36] font-semibold' : 'text-slate-600 hover:text-[#0f1e36]' }}">
                        Cari Mobil
                    </a>
                    <a href="{{ route('brands.index') }}"
                       class="text-sm font-medium transition-colors {{ request()->routeIs('brands.index') ? 'text-[#0f1e36] font-semibold' : 'text-slate-600 hover:text-[#0f1e36]' }}">
                        Merek
                    </a>
                    <a href="{{ route('how-it-works') }}"
                       class="text-sm font-medium transition-colors {{ request()->routeIs('how-it-works') ? 'text-[#0f1e36] font-semibold' : 'text-slate-600 hover:text-[#0f1e36]' }}">
                        Cara Kerja
                    </a>
                </div>

                {{-- Right Side --}}
                <div class="flex items-center gap-3">
                    @auth
                        {{-- Post a Car button --}}
                        <a href="{{ route('cars.create') }}"
                           class="hidden md:inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Pasang Iklan
                        </a>

                        {{-- User Dropdown --}}
                        <div class="relative" x-data="{ userOpen: false }">
                            <button @click="userOpen = !userOpen"
                                    class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100 transition-colors">
                                <div class="w-8 h-8 bg-[#0f1e36] text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden md:block text-sm font-medium text-slate-700 max-w-[100px] truncate">
                                    {{ auth()->user()->name }}
                                </span>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{ 'rotate-180': userOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div x-show="userOpen"
                                 @click.outside="userOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50"
                                 style="display: none;">
                                <div class="px-4 py-2 border-b border-slate-100 mb-1">
                                    <p class="text-xs text-slate-400">Masuk sebagai</p>
                                    <p class="text-sm font-semibold text-slate-700 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('my-ads') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Garasi Saya
                                </a>
                                <a href="{{ route('cars.create') }}"
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Pasang Iklan
                                </a>
                                <div class="border-t border-slate-100 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors text-left">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Guest buttons --}}
                        <a href="{{ route('login') }}"
                           class="hidden md:inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-100 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="hidden md:inline-flex items-center px-4 py-2 rounded-xl bg-[#0f1e36] hover:bg-[#1a3057] text-white text-sm font-semibold transition-colors shadow-sm">
                            Daftar Gratis
                        </a>
                    @endauth

                    {{-- Mobile Hamburger --}}
                    <button @click="mobileOpen = !mobileOpen"
                            class="md:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors">
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== Mobile Menu ===== --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden border-t border-slate-100 bg-white"
             style="display: none;">
            <div class="px-4 pt-3 pb-4 space-y-1">
                <a href="{{ route('cars.index') }}"
                   class="block px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('cars.index') ? 'bg-slate-100 text-[#0f1e36] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    🔍 Cari Mobil
                </a>
                <a href="{{ route('brands.index') }}"
                   class="block px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('brands.index') ? 'bg-slate-100 text-[#0f1e36] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    🏷️ Merek
                </a>
                <a href="{{ route('how-it-works') }}"
                   class="block px-3 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('how-it-works') ? 'bg-slate-100 text-[#0f1e36] font-semibold' : 'text-slate-700 hover:bg-slate-50' }}">
                    ℹ️ Cara Kerja
                </a>
            </div>

            {{-- Mobile Auth --}}
            <div class="border-t border-slate-100 px-4 py-3">
                @auth
                    <div class="flex items-center gap-3 mb-3 px-1">
                        <div class="w-9 h-9 bg-[#0f1e36] text-white rounded-full flex items-center justify-center text-sm font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('my-ads') }}" class="block px-3 py-2.5 rounded-xl text-sm text-slate-700 hover:bg-slate-50">🚗 Garasi Saya</a>
                    <a href="{{ route('cars.create') }}" class="block px-3 py-2.5 rounded-xl text-sm text-slate-700 hover:bg-slate-50">➕ Pasang Iklan</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2.5 rounded-xl text-sm text-red-500 hover:bg-red-50">
                            🚪 Keluar
                        </button>
                    </form>
                @else
                    <div class="flex gap-2">
                        <a href="{{ route('login') }}"
                           class="flex-1 text-center px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}"
                           class="flex-1 text-center px-4 py-2.5 rounded-xl bg-[#0f1e36] text-white text-sm font-semibold hover:bg-[#1a3057] transition-colors">
                            Daftar Gratis
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
    {{-- ===================== END NAVBAR ===================== --}}

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-20 right-4 z-50 bg-green-500 text-white px-5 py-3 rounded-2xl shadow-lg flex items-center gap-2 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-20 right-4 z-50 bg-red-500 text-white px-5 py-3 rounded-2xl shadow-lg flex items-center gap-2 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Main Content --}}
    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="bg-[#0f1e36] text-slate-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                {{-- Brand Column --}}
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-extrabold text-white">Carvia<span class="text-blue-400">.id</span></span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Platform jual beli mobil bekas terpercaya. Hubungi penjual langsung via WhatsApp, tanpa perantara.
                    </p>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">Jelajahi</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('cars.index') }}" class="hover:text-white transition-colors">Cari Mobil</a></li>
                        <li><a href="{{ route('brands.index') }}" class="hover:text-white transition-colors">Semua Merek</a></li>
                        <li><a href="{{ route('how-it-works') }}" class="hover:text-white transition-colors">Cara Kerja</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">Akun</h4>
                    <ul class="space-y-2.5 text-sm">
                        @auth
                            <li><a href="{{ route('my-ads') }}" class="hover:text-white transition-colors">Garasi Saya</a></li>
                            <li><a href="{{ route('cars.create') }}" class="hover:text-white transition-colors">Pasang Iklan</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar Gratis</a></li>
                        @endauth
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">Kontak</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                <path d="M12 0C5.374 0 0 5.373 0 12c0 2.136.564 4.141 1.544 5.878L0 24l6.336-1.512A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.001-1.364l-.36-.214-3.727.977.994-3.63-.235-.374A9.817 9.817 0 012.182 12C2.182 6.597 6.598 2.182 12 2.182S21.818 6.597 21.818 12 17.403 21.818 12 21.818z"/>
                            </svg>
                            <span>WhatsApp Only</span>
                        </li>
                        <li class="text-slate-500 text-xs">Hubungi penjual langsung</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 mt-10 pt-6 flex flex-col md:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Carvia.id — Hak cipta dilindungi.</p>
                <p>Built with ❤️ using Laravel & Tailwind CSS</p>
            </div>
        </div>
    </footer>
    {{-- ===================== END FOOTER ===================== --}}

    {{-- Page-specific scripts --}}
    @stack('scripts')

</body>
</html>
