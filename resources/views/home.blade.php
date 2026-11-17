@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-14">

    <!-- 1. Hero Section — full width -->
    <div class="w-full mb-10">
        <span class="inline-block text-xs font-extrabold tracking-widest uppercase text-blue-500 bg-blue-50 px-3 py-1 rounded-full mb-4">
            PEER-TO-PEER USED CAR MARKETPLACE
        </span>
        <h1 class="text-4xl sm:text-5xl xl:text-6xl font-extrabold text-[#0f1e36] tracking-tight leading-[1.1] mb-5">
            Official-brand used cars,<br>
            sold directly by<br>
            <span class="text-blue-500">private owners.</span>
        </h1>
        <p class="text-slate-500 text-base sm:text-lg leading-relaxed mb-8 max-w-3xl">
            No dealership markups or middlemen. Anyone can publish a car listing in minutes, and buyers get the seller's direct WhatsApp inside every ad.
        </p>

        <!-- Search Bar — full width -->
        <form action="{{ route('cars.index') }}" method="GET" class="relative flex items-center max-w-3xl mb-4">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       name="q"
                       placeholder="Cari merek, model, atau kota..."
                       class="w-full pl-11 pr-36 py-4 rounded-2xl bg-white border border-slate-200 shadow-sm text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0f1e36] focus:border-transparent transition">
            </div>
            <button type="submit"
                    class="absolute right-2 px-5 sm:px-6 py-2.5 rounded-xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white text-sm font-bold shadow transition active:scale-95">
                Cari Mobil
            </button>
        </form>

        <!-- Popular Brands Pills -->
        @if($popularBrands->isNotEmpty())
            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <span class="font-medium">Merek populer:</span>
                @foreach($popularBrands as $brand)
                    <a href="{{ route('cars.index', ['brand' => $brand->slug]) }}"
                       class="px-3 py-1 rounded-full bg-white border border-slate-200 font-semibold text-[#0f1e36] hover:bg-[#0f1e36] hover:text-white hover:border-[#0f1e36] transition text-xs">
                        {{ $brand->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 2. Featured Showcase Carousel -->
    @if($featuredCars->isNotEmpty())
        <div class="relative rounded-3xl overflow-hidden shadow-sm border border-slate-200/80 bg-slate-900 aspect-[16/9] sm:aspect-[21/9] max-h-[520px] mb-8" id="featuredCarousel">
            @foreach($featuredCars as $index => $car)
                <div class="carousel-slide absolute inset-0 transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}" 
                     data-index="{{ $index }}">
                    <!-- Car Image with gradient overlay -->
                    <img src="{{ $car->primaryImage ? $car->primaryImage->path : 'https://placehold.co/1200x600?text=Mobil+Bekas' }}" 
                         alt="{{ $car->title }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>

                    <!-- Slide Bottom Overlay Info -->
                    <div class="absolute bottom-0 inset-x-0 p-6 sm:p-10 flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
                        <div class="max-w-xl text-white">
                            <span class="inline-block text-xs font-bold uppercase tracking-wider text-blue-400 bg-blue-950/60 px-3 py-1 rounded-md mb-2 border border-blue-800/50">
                                {{ $car->carModel->brand->name }} • {{ $car->manufacture_year }}
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-1">
                                {{ $car->title }}
                            </h2>
                            <p class="text-slate-300 text-sm sm:text-base font-medium">
                                <span class="text-white font-extrabold text-lg">Rp {{ number_format($car->price, 0, ',', '.') }}</span>
                                <span class="mx-2">•</span>
                                {{ $car->city->name }}
                                <span class="mx-2">•</span>
                                <span class="text-emerald-400 font-semibold">Verified Private Seller</span>
                            </p>
                        </div>

                        <div class="flex items-center gap-4">
                            <a href="{{ route('cars.show', $car->slug) }}" 
                               class="px-6 py-3 rounded-xl bg-white text-[#0f1e36] hover:bg-slate-100 font-bold text-sm shadow transition active:scale-95">
                                View Listing
                            </a>
                            <span class="text-xs font-bold tracking-wider text-slate-300 bg-black/40 backdrop-blur-sm px-3 py-1.5 rounded-lg border border-white/10">
                                {{ $index + 1 }} / {{ $featuredCars->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Carousel Navigation Arrows — always visible -->
            <button id="prevSlideBtn" type="button"
                    class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center backdrop-blur-sm transition focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button id="nextSlideBtn" type="button"
                    class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center backdrop-blur-sm transition focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Carousel Pagination Dots -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
                @foreach($featuredCars as $index => $car)
                    <button type="button" 
                            class="carousel-dot h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'w-8 bg-white' : 'w-2 bg-white/50' }}" 
                            data-slide="{{ $index }}"></button>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 3. 4 Value Props Grid (Why P2P) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-16">
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow transition">
            <h3 class="font-extrabold text-[#0f1e36] text-base mb-1.5">Official Car Brands</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                Browse verified makes from Toyota, Honda, Mitsubishi, BMW, and 10+ popular official manufacturers.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow transition">
            <h3 class="font-extrabold text-[#0f1e36] text-base mb-1.5">Open to Anyone</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                Any private owner can post their vehicle ad and photos in under 2 minutes with instant live publication.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow transition">
            <h3 class="font-extrabold text-[#0f1e36] text-base mb-1.5">Direct Seller Contact</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                Every listing shows the owner's WhatsApp number and phone so serious buyers can reach out immediately.
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow transition">
            <h3 class="font-extrabold text-[#0f1e36] text-base mb-1.5">No Dealer Fees</h3>
            <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                Negotiate and inspect directly with the car owner with zero markup and zero middleman commissions.
            </p>
        </div>
    </div>

    <!-- 4. How It Works Section Banner -->
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200/80 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10 pb-8 border-b border-slate-100">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0f1e36] tracking-tight">How Carvia Works</h2>
                <p class="text-slate-600 mt-1 text-sm sm:text-base">Built for everyday drivers selling official-brand cars directly to buyers.</p>
            </div>
            <a href="{{ route('cars.create') }}" 
               class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white font-bold text-sm shadow transition shrink-0 active:scale-95">
                Post Your Vehicle
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="flex flex-col">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Step 01</span>
                <h4 class="text-lg font-bold text-[#0f1e36] mb-2">List Your Official-Brand Car</h4>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Select your vehicle's make, model, mileage, and asking price. Specify BPKB & tax validity, upload photos from your phone, and publish instantly.
                </p>
            </div>

            <div class="flex flex-col">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Step 02</span>
                <h4 class="text-lg font-bold text-[#0f1e36] mb-2">Buyers See Your Contact Info</h4>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Your ad displays your phone number and pre-filled WhatsApp link directly in the listing so serious buyers can message you right away.
                </p>
            </div>

            <div class="flex flex-col">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Step 03</span>
                <h4 class="text-lg font-bold text-[#0f1e36] mb-2">Meet, Inspect & Close Directly</h4>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Coordinate test drives and document checking on your own terms with zero dealer commission, then mark your ad as Sold in one click.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');
        const prevBtn = document.getElementById('prevSlideBtn');
        const nextBtn = document.getElementById('nextSlideBtn');
        let currentIndex = 0;
        let slideInterval;

        function showSlide(index) {
            if (slides.length === 0) return;
            
            // Normalize index
            if (index >= slides.length) currentIndex = 0;
            else if (index < 0) currentIndex = slides.length - 1;
            else currentIndex = index;

            slides.forEach((slide, i) => {
                if (i === currentIndex) {
                    slide.classList.remove('opacity-0', 'pointer-events-none');
                    slide.classList.add('opacity-100', 'z-10');
                } else {
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.classList.add('opacity-0', 'pointer-events-none');
                }
            });

            dots.forEach((dot, i) => {
                if (i === currentIndex) {
                    dot.classList.remove('w-2', 'bg-white/50');
                    dot.classList.add('w-8', 'bg-white');
                } else {
                    dot.classList.remove('w-8', 'bg-white');
                    dot.classList.add('w-2', 'bg-white/50');
                }
            });
        }

        function nextSlide() {
            showSlide(currentIndex + 1);
        }

        function prevSlide() {
            showSlide(currentIndex - 1);
        }

        if (nextBtn && prevBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
                resetAutoplay();
            });
            prevBtn.addEventListener('click', () => {
                prevSlide();
                resetAutoplay();
            });
        }

        dots.forEach(dot => {
            dot.addEventListener('click', (e) => {
                const target = parseInt(e.target.dataset.slide);
                showSlide(target);
                resetAutoplay();
            });
        });

        function startAutoplay() {
            slideInterval = setInterval(nextSlide, 5000);
        }

        function resetAutoplay() {
            clearInterval(slideInterval);
            startAutoplay();
        }

        if (slides.length > 1) {
            startAutoplay();
        }
    });
</script>
@endpush
