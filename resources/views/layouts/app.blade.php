<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - Carvia' : 'Carvia - Marketplace Mobil Bekas P2P' }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Assets / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-[#eef2f6] text-[#0f172a] font-sans antialiased min-h-screen flex flex-col selection:bg-[#0f1e36] selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="bg-white/95 backdrop-blur-sm sticky top-0 z-50 border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo Brand -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <span class="text-2xl font-extrabold tracking-tight text-[#0f1e36] group-hover:opacity-90 transition">
                            Carvia
                        </span>
                        <span class="inline-block w-2 h-2 rounded-full bg-blue-600"></span>
                    </a>

                    <!-- Desktop Nav Links -->
                    <nav class="hidden md:flex items-center gap-7">
                        <a href="{{ route('cars.index') }}" 
                           class="text-sm font-semibold transition {{ request()->routeIs('cars.index') ? 'text-[#0f1e36] font-bold' : 'text-slate-600 hover:text-[#0f1e36]' }}">
                            Browse Cars
                        </a>
                        <a href="{{ route('brands.index') }}" 
                           class="text-sm font-semibold transition {{ request()->routeIs('brands.*') ? 'text-[#0f1e36] font-bold' : 'text-slate-600 hover:text-[#0f1e36]' }}">
                            Brands
                        </a>
                        <a href="{{ route('how-it-works') }}" 
                           class="text-sm font-semibold transition {{ request()->routeIs('how-it-works') ? 'text-[#0f1e36] font-bold' : 'text-slate-600 hover:text-[#0f1e36]' }}">
                            How It Works
                        </a>
                        <a href="{{ route('my-ads') }}" 
                           class="text-sm font-semibold transition {{ request()->routeIs('my-ads') ? 'text-[#0f1e36] font-bold' : 'text-slate-600 hover:text-[#0f1e36]' }}">
                            My Ads
                        </a>
                    </nav>
                </div>

                <!-- Right Action Button -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('cars.create') }}" 
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white text-sm font-bold tracking-tight shadow-sm hover:shadow transition duration-150 active:scale-95">
                        Post a Car
                    </a>

                    <!-- Mobile Hamburger Button -->
                    <button id="mobileMenuBtn" type="button" 
                            class="md:hidden p-2 rounded-lg text-slate-600 hover:text-[#0f1e36] hover:bg-slate-100 focus:outline-none"
                            aria-label="Toggle Navigation">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Nav Menu (Hidden by default) -->
            <div id="mobileMenu" class="hidden md:hidden py-4 border-t border-slate-100 flex flex-col gap-3">
                <a href="{{ route('cars.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50">Browse Cars</a>
                <a href="{{ route('brands.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50">Brands</a>
                <a href="{{ route('how-it-works') }}" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50">How It Works</a>
                <a href="{{ route('my-ads') }}" class="px-3 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50">My Ads</a>
                <a href="{{ route('cars.create') }}" class="mt-2 text-center py-2.5 rounded-xl bg-[#0f1e36] text-white text-sm font-bold">Post a Car</a>
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Deep Navy Footer -->
    <footer class="bg-[#0a1424] text-slate-300 mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 pb-10 border-b border-slate-800/80">
                <div class="max-w-md">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-2xl font-extrabold tracking-tight text-white">Carvia</span>
                        <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Independent peer-to-peer used car marketplace. All listings are posted and managed directly by private sellers without dealership markups.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-6 md:gap-10 text-sm font-medium">
                    <a href="{{ route('cars.index') }}" class="text-slate-300 hover:text-white transition">Browse Cars</a>
                    <a href="{{ route('brands.index') }}" class="text-slate-300 hover:text-white transition">Brands</a>
                    <a href="{{ route('how-it-works') }}" class="text-slate-300 hover:text-white transition">How It Works</a>
                    <a href="{{ route('cars.create') }}" class="text-slate-300 hover:text-white transition">Post a Car</a>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Carvia Indonesia. Transaksi langsung antara pembeli dan pemilik unit.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('how-it-works') }}" class="hover:text-slate-300 transition">Tips Transaksi Aman</a>
                    <span>•</span>
                    <span class="text-emerald-400 font-medium">WhatsApp Direct Connect</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Nav Script -->
    <script>
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
