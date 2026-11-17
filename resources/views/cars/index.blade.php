@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-block text-xs font-extrabold tracking-widest uppercase text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                    KATALOG MOBIL BEKAS
                </span>
                @if(request()->boolean('include_sold'))
                    <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">
                        Termasuk Terjual
                    </span>
                @endif
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f1e36] tracking-tight">
                Pilihan Mobil Berkualitas Langsung dari Pemilik
            </h1>
            <p class="text-slate-500 mt-1.5 text-sm sm:text-base">
                Beli mobil bekas resmi tanpa markup dealer. Hubungi langsung pemilik via WhatsApp.
            </p>
        </div>
        <div class="text-sm font-semibold text-slate-500 bg-white px-4 py-2.5 rounded-2xl border border-slate-200 shadow-sm self-start md:self-auto shrink-0">
            Ditemukan <span class="text-[#0f1e36] font-extrabold text-base">{{ $totalCars }}</span> unit mobil
        </div>
    </div>

    <!-- Filter Container Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm mb-8" x-data="{ advanced: false }">
        
        <!-- Horizontal Brand Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 scrollbar-none border-b border-slate-100 mb-6">
            <a href="{{ route('cars.index', request()->except('brand', 'page')) }}" 
               class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition shrink-0 {{ !request('brand') ? 'bg-[#0f1e36] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Merek
            </a>
            @foreach($brands as $brand)
                <a href="{{ route('cars.index', array_merge(request()->except('page'), ['brand' => $brand->slug])) }}" 
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition shrink-0 {{ request('brand') === $brand->slug ? 'bg-[#0f1e36] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $brand->name }}
                </a>
            @endforeach
        </div>

        <!-- Filter Controls Form -->
        <form action="{{ route('cars.index') }}" method="GET">
            @if(request('brand'))
                <input type="hidden" name="brand" value="{{ request('brand') }}">
            @endif

            <!-- Top Search & Quick Controls -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 mb-4">
                
                <!-- Keyword Search (5 cols) -->
                <div class="lg:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}"
                           placeholder="Cari judul, model, atau spesifikasi..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Kota (3 cols) -->
                <div class="lg:col-span-3">
                    <select name="city_id" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="">Semua Lokasi / Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort By (3 cols) -->
                <div class="lg:col-span-3">
                    <select name="sort" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Urutkan: Iklan Terbaru</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                        <option value="mileage_low" {{ request('sort') == 'mileage_low' ? 'selected' : '' }}>Kilometer: Terendah</option>
                        <option value="year_high" {{ request('sort') == 'year_high' ? 'selected' : '' }}>Tahun: Paling Baru</option>
                        <option value="year_low" {{ request('sort') == 'year_low' ? 'selected' : '' }}>Tahun: Paling Lama</option>
                    </select>
                </div>

                <!-- Submit Button (1 col) -->
                <div class="lg:col-span-1">
                    <button type="submit" 
                            class="w-full h-full py-2.5 px-3 rounded-xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white font-bold text-sm shadow transition flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Toggle Filter Lengkap -->
            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <button type="button" 
                        @click="advanced = !advanced" 
                        class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 transition">
                    <span x-text="advanced ? 'Sembunyikan Filter Tambahan' : 'Tampilkan Filter Lengkap (Bodi, Transmisi, Bahan Bakar, Harga)'"></span>
                    <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': advanced }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                @if(request()->hasAny(['q', 'brand', 'city_id', 'body_type', 'transmission', 'fuel_type', 'min_price', 'max_price', 'sort', 'include_sold']))
                    <a href="{{ route('cars.index') }}" class="text-xs font-semibold text-rose-500 hover:text-rose-700 transition">
                        Reset Semua Filter ✕
                    </a>
                @endif
            </div>

            <!-- Advanced Filter Grid (Collapsible) -->
            <div x-show="advanced" 
                 x-collapse
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-5 mt-4 border-t border-slate-100">
                
                <!-- Body Style -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tipe Bodi</label>
                    <select name="body_type" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="">Semua Tipe Bodi</option>
                        <option value="suv" {{ request('body_type') == 'suv' ? 'selected' : '' }}>SUV</option>
                        <option value="mpv" {{ request('body_type') == 'mpv' ? 'selected' : '' }}>MPV</option>
                        <option value="sedan" {{ request('body_type') == 'sedan' ? 'selected' : '' }}>Sedan</option>
                        <option value="hatchback" {{ request('body_type') == 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                        <option value="city_car" {{ request('body_type') == 'city_car' ? 'selected' : '' }}>City Car</option>
                    </select>
                </div>

                <!-- Transmisi -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Transmisi</label>
                    <select name="transmission" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="">Semua Transmisi</option>
                        <option value="automatic" {{ request('transmission') == 'automatic' ? 'selected' : '' }}>Otomatis (Automatic)</option>
                        <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>

                <!-- Bahan Bakar -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Bahan Bakar</label>
                    <select name="fuel_type" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="">Semua Bahan Bakar</option>
                        <option value="bensin" {{ request('fuel_type') == 'bensin' ? 'selected' : '' }}>Bensin</option>
                        <option value="diesel" {{ request('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="hybrid" {{ request('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="electric" {{ request('fuel_type') == 'electric' ? 'selected' : '' }}>Listrik (Electric)</option>
                    </select>
                </div>

                <!-- Harga Maksimal -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Harga Maksimal</label>
                    <select name="max_price" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="">Tanpa Batas Harga</option>
                        <option value="150000000" {{ request('max_price') == '150000000' ? 'selected' : '' }}>< Rp 150 Juta</option>
                        <option value="250000000" {{ request('max_price') == '250000000' ? 'selected' : '' }}>< Rp 250 Juta</option>
                        <option value="350000000" {{ request('max_price') == '350000000' ? 'selected' : '' }}>< Rp 350 Juta</option>
                        <option value="500000000" {{ request('max_price') == '500000000' ? 'selected' : '' }}>< Rp 500 Juta</option>
                        <option value="750000000" {{ request('max_price') == '750000000' ? 'selected' : '' }}>< Rp 750 Juta</option>
                    </select>
                </div>

                <!-- Apply Button Inside Advanced Filter -->
                <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-2 pt-2">
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white text-xs font-bold shadow-sm transition">
                        Terapkan Filter
                    </button>
                </div>

            </div>
        </form>

        <!-- Active Filter Badges -->
        @if(request()->hasAny(['q', 'brand', 'city_id', 'body_type', 'transmission', 'fuel_type', 'max_price']))
            <div class="flex flex-wrap items-center gap-2 mt-4 pt-3 border-t border-slate-100 text-xs">
                <span class="text-slate-400 font-semibold">Filter aktif:</span>

                @if(request('q'))
                    <a href="{{ route('cars.index', request()->except('q', 'page')) }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200">
                        <span>Pencarian: "{{ request('q') }}"</span>
                        <span class="text-slate-400 hover:text-slate-700 font-bold">✕</span>
                    </a>
                @endif

                @if(request('brand'))
                    <a href="{{ route('cars.index', request()->except('brand', 'page')) }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200">
                        <span>Merek: {{ strtoupper(request('brand')) }}</span>
                        <span class="text-slate-400 hover:text-slate-700 font-bold">✕</span>
                    </a>
                @endif

                @if(request('city_id'))
                    @php $chosenCity = $cities->firstWhere('id', request('city_id')); @endphp
                    @if($chosenCity)
                        <a href="{{ route('cars.index', request()->except('city_id', 'page')) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200">
                            <span>Lokasi: {{ $chosenCity->name }}</span>
                            <span class="text-slate-400 hover:text-slate-700 font-bold">✕</span>
                        </a>
                    @endif
                @endif

                @if(request('transmission'))
                    <a href="{{ route('cars.index', request()->except('transmission', 'page')) }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200">
                        <span>Transmisi: {{ ucfirst(request('transmission')) }}</span>
                        <span class="text-slate-400 hover:text-slate-700 font-bold">✕</span>
                    </a>
                @endif

                @if(request('body_type'))
                    <a href="{{ route('cars.index', request()->except('body_type', 'page')) }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200">
                        <span>Bodi: {{ strtoupper(request('body_type')) }}</span>
                        <span class="text-slate-400 hover:text-slate-700 font-bold">✕</span>
                    </a>
                @endif

                @if(request('fuel_type'))
                    <a href="{{ route('cars.index', request()->except('fuel_type', 'page')) }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200">
                        <span>Bahan Bakar: {{ ucfirst(request('fuel_type')) }}</span>
                        <span class="text-slate-400 hover:text-slate-700 font-bold">✕</span>
                    </a>
                @endif

                @if(request('max_price'))
                    <a href="{{ route('cars.index', request()->except('max_price', 'page')) }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200">
                        <span>Harga: ≤ Rp {{ number_format(request('max_price') / 1000000, 0) }} Juta</span>
                        <span class="text-slate-400 hover:text-slate-700 font-bold">✕</span>
                    </a>
                @endif
            </div>
        @endif

    </div>

    <!-- Car Cards Grid (2 Kolom sesuai identitas Carvia) -->
    @if($cars->isEmpty())
        <div class="bg-white rounded-3xl p-12 sm:p-16 text-center border border-slate-200/80 shadow-sm">
            <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <h3 class="text-lg font-extrabold text-[#0f1e36] mb-1">Tidak Ada Mobil yang Sesuai Kriteria</h3>
            <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">
                Coba sesuaikan kata kunci, perluas rentang harga, atau reset filter untuk melihat semua stok mobil yang tersedia.
            </p>
            <a href="{{ route('cars.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white text-xs font-bold shadow transition">
                <span>Reset Semua Filter</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            @foreach($cars as $car)
                <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-sm hover:shadow-md hover:border-slate-300 transition duration-200 group flex flex-col justify-between">
                    
                    <!-- Top Section: Image & Header -->
                    <div>
                        <!-- Car Photo & Badges -->
                        <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                            <a href="{{ route('cars.show', $car->slug) }}">
                                <img src="{{ $car->primaryImage ? $car->primaryImage->path : 'https://placehold.co/800x500?text=Mobil+Bekas' }}" 
                                     alt="{{ $car->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out">
                            </a>

                            <!-- Floating Badges -->
                            <div class="absolute top-3.5 left-3.5 flex flex-wrap gap-1.5">
                                <span class="px-3 py-1 rounded-lg bg-black/60 backdrop-blur-sm text-white font-bold text-xs uppercase tracking-wider border border-white/10">
                                    {{ $car->carModel->brand->name }} • {{ $car->manufacture_year }}
                                </span>
                                @if($car->fuel_type)
                                    <span class="px-2.5 py-1 rounded-lg bg-white/90 backdrop-blur-sm text-slate-800 font-bold text-xs capitalize shadow-sm">
                                        {{ $car->fuel_type }}
                                    </span>
                                @endif
                            </div>

                            @if($car->status === 'sold')
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                    <span class="px-5 py-2 rounded-xl bg-red-600 text-white font-extrabold text-sm uppercase tracking-widest shadow-lg">
                                        TERJUAL (SOLD)
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 pb-4">
                            <!-- Model Title -->
                            <h3 class="text-xl font-extrabold text-[#0f1e36] tracking-tight group-hover:text-blue-600 transition line-clamp-1 mb-2">
                                <a href="{{ route('cars.show', $car->slug) }}">
                                    {{ $car->title }}
                                </a>
                            </h3>

                            <!-- Price -->
                            <div class="text-2xl font-black text-[#0f1e36] tracking-tight mb-3 flex items-baseline gap-1.5">
                                <span>Rp {{ number_format($car->price, 0, ',', '.') }}</span>
                                @if($car->price_negotiable)
                                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">Nego</span>
                                @endif
                            </div>

                            <!-- Key Specs Line -->
                            <div class="text-xs font-semibold text-slate-600 flex flex-wrap items-center gap-1.5">
                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg text-slate-700">{{ number_format($car->mileage_km, 0, ',', '.') }} km</span>
                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg text-slate-700 capitalize">{{ $car->transmission }}</span>
                                <span class="bg-slate-100 px-2.5 py-1 rounded-lg text-slate-700">{{ $car->city->name }}</span>
                                @if($car->plate_region)
                                    <span class="bg-slate-100 px-2.5 py-1 rounded-lg text-slate-700">Plat {{ $car->plate_region }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Bar: Seller Info & Action -->
                    <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-[#0f1e36] text-white flex items-center justify-center font-bold text-[10px]">
                                {{ strtoupper(substr($car->user->name, 0, 1)) }}
                            </div>
                            <span class="text-slate-600 font-medium">
                                <strong class="text-slate-900">{{ $car->user->name }}</strong>
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('cars.show', $car->slug) }}" 
                               class="font-bold text-[#0f1e36] hover:text-blue-600 transition flex items-center gap-1.5 py-1 px-2.5 rounded-lg hover:bg-white">
                                @guest
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span>Lihat Kontak</span>
                                @else
                                    <span>Detail & Kontak</span>
                                    <span>&rarr;</span>
                                @endguest
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $cars->links() }}
        </div>
    @endif

</div>
@endsection
