@extends('layouts.app')

@section('content')
@php
    $imageList = $car->images->isNotEmpty() 
        ? $car->images->pluck('path')->toArray() 
        : [($car->primaryImage ? $car->primaryImage->path : 'https://placehold.co/1200x800?text=Mobil+Bekas')];
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12" 
     x-data="{ 
         images: {{ json_encode($imageList) }},
         currentIndex: 0,
         next() {
             this.currentIndex = (this.currentIndex + 1) % this.images.length;
         },
         prev() {
             this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
         },
         copied: false,
         copyLink() {
             navigator.clipboard.writeText(window.location.href);
             this.copied = true;
             setTimeout(() => this.copied = false, 2500);
         }
     }">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6 overflow-x-auto whitespace-nowrap">
        <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('cars.index') }}" class="hover:text-slate-800 transition">Katalog Mobil</a>
        <span>/</span>
        <a href="{{ route('cars.index', ['brand' => $car->carModel->brand->slug]) }}" class="hover:text-slate-800 transition">
            {{ $car->carModel->brand->name }}
        </a>
        <span>/</span>
        <span class="text-[#0f1e36] font-bold truncate max-w-xs">{{ $car->title }}</span>
    </nav>

    <!-- Main Grid: Left (Photos & Details) + Right (Sticky Seller Box) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start mb-16">
        
        <!-- Left Column: 2 Spans -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Photo Gallery Card -->
            <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/90 shadow-sm">
                <!-- Main Big Photo Container with Carousel Controls -->
                <div class="aspect-[16/10] sm:aspect-[16/9] rounded-2xl overflow-hidden bg-slate-950 relative group">
                    <img :src="images[currentIndex]" 
                         alt="{{ $car->title }}" 
                         class="w-full h-full object-cover transition-opacity duration-300">
                    
                    @if($car->status === 'sold')
                        <div class="absolute inset-0 bg-black/65 backdrop-blur-xs flex items-center justify-center z-10">
                            <span class="px-6 py-2.5 rounded-2xl bg-rose-600 text-white font-extrabold text-base tracking-widest uppercase shadow-xl">
                                UNIT SUDAH TERJUAL (SOLD)
                            </span>
                        </div>
                    @endif

                    <!-- Top Badges Overlay -->
                    <div class="absolute top-4 left-4 flex flex-wrap gap-2 z-10">
                        <span class="px-3 py-1 rounded-xl bg-black/60 backdrop-blur-sm text-white font-bold text-xs uppercase tracking-wider border border-white/10">
                            {{ $car->carModel->brand->name }} • {{ $car->manufacture_year }}
                        </span>
                        <span class="px-3 py-1 rounded-xl bg-blue-600 text-white font-bold text-xs capitalize shadow">
                            {{ $car->carModel->body_type ?? 'Sedan' }}
                        </span>
                    </div>

                    <!-- Slide Counter Badge -->
                    <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-xl border border-white/10 z-10">
                        <span x-text="(currentIndex + 1) + ' / ' + images.length"></span> Foto
                    </div>

                    <!-- Carousel Prev/Next Buttons (Manual Slider, no autoplay) -->
                    <template x-if="images.length > 1">
                        <div>
                            <button type="button" 
                                    @click="prev()"
                                    aria-label="Foto Sebelumnya"
                                    class="absolute left-3.5 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-black/55 hover:bg-black/85 text-white flex items-center justify-center backdrop-blur-sm shadow-md transition hover:scale-105 active:scale-95 z-10">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button type="button" 
                                    @click="next()"
                                    aria-label="Foto Selanjutnya"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-black/55 hover:bg-black/85 text-white flex items-center justify-center backdrop-blur-sm shadow-md transition hover:scale-105 active:scale-95 z-10">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Thumbnails Gallery -->
                <template x-if="images.length > 1">
                    <div class="flex items-center gap-3 overflow-x-auto pt-4 pb-1 scrollbar-none">
                        <template x-for="(img, idx) in images" :key="idx">
                            <button type="button" 
                                    @click="currentIndex = idx"
                                    :class="currentIndex === idx ? 'ring-2 ring-[#0f1e36] opacity-100 scale-95 shadow' : 'opacity-70 hover:opacity-100'"
                                    class="w-20 h-16 sm:w-24 sm:h-18 rounded-xl overflow-hidden border border-slate-200 transition shrink-0 bg-slate-100">
                                <img :src="img" alt="Thumbnail" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Title & Pricing Block -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                
                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                            NIK {{ $car->manufacture_year }}
                        </span>
                        @if($car->registration_year)
                            <span class="px-3 py-1 rounded-lg text-xs font-bold text-blue-700 bg-blue-50">
                                Pemakaian {{ $car->registration_year }}
                            </span>
                        @endif
                        <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $car->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700' }}">
                            {{ $car->status === 'active' ? '● TERSEDIA' : 'TERJUAL' }}
                        </span>
                    </div>

                    <!-- Share Button -->
                    <button type="button" 
                            @click="copyLink()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        <span x-text="copied ? 'Tautan Disalin! ✓' : 'Bagikan'"></span>
                    </button>
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#0f1e36] tracking-tight leading-tight mb-4">
                    {{ $car->title }}
                </h1>

                <!-- Price and Badges -->
                <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-3 pb-6 border-b border-slate-100">
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl sm:text-4xl font-black text-[#0f1e36] tracking-tight">
                            Rp {{ number_format($car->price, 0, ',', '.') }}
                        </span>
                        @if($car->price_negotiable)
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                                Bisa Nego
                            </span>
                        @else
                            <span class="text-xs font-semibold text-slate-400">Harga Pas / Nett</span>
                        @endif
                    </div>
                    <span class="text-xs font-medium text-slate-400">
                        Dilihat {{ number_format($car->view_count) }} kali
                    </span>
                </div>

                <!-- Trust Badges Checklist -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-6">
                    <!-- BPKB -->
                    <div class="flex items-center gap-2.5 text-xs font-bold text-emerald-900 bg-emerald-50/90 p-3 rounded-2xl border border-emerald-200/70">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>BPKB: {{ $car->bpkb_status === 'on_hand' ? 'Ready (On Hand)' : 'Di Leasing' }}</span>
                    </div>

                    <!-- Bebas Tabrakan -->
                    <div class="flex items-center gap-2.5 text-xs font-bold {{ $car->is_accident_free ? 'text-emerald-900 bg-emerald-50/90 border-emerald-200/70' : 'text-slate-700 bg-slate-50 border-slate-200' }} p-3 rounded-2xl border">
                        <svg class="w-4 h-4 {{ $car->is_accident_free ? 'text-emerald-600' : 'text-slate-400' }} shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $car->is_accident_free ? 'Bebas Tabrakan' : 'Ada Riwayat Insiden' }}</span>
                    </div>

                    <!-- Bebas Banjir -->
                    <div class="flex items-center gap-2.5 text-xs font-bold {{ $car->is_flood_free ? 'text-emerald-900 bg-emerald-50/90 border-emerald-200/70' : 'text-slate-700 bg-slate-50 border-slate-200' }} p-3 rounded-2xl border">
                        <svg class="w-4 h-4 {{ $car->is_flood_free ? 'text-emerald-600' : 'text-slate-400' }} shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $car->is_flood_free ? 'Bebas Banjir' : 'Riwayat Banjir' }}</span>
                    </div>

                    <!-- Kepemilikan -->
                    <div class="flex items-center gap-2.5 text-xs font-bold text-slate-700 bg-slate-50 p-3 rounded-2xl border border-slate-200">
                        <span class="text-base">👤</span>
                        <span>Tangan ke-{{ $car->owner_count }} ({{ ucfirst($car->registration_type) }})</span>
                    </div>

                    <!-- Kunci Cadangan -->
                    <div class="flex items-center gap-2.5 text-xs font-bold text-slate-700 bg-slate-50 p-3 rounded-2xl border border-slate-200">
                        <span class="text-base">🔑</span>
                        <span>Kunci Serep: {{ $car->has_spare_key ? 'Ada Lengkap' : 'Tidak Ada' }}</span>
                    </div>

                    <!-- Buku Servis -->
                    <div class="flex items-center gap-2.5 text-xs font-bold text-slate-700 bg-slate-50 p-3 rounded-2xl border border-slate-200">
                        <span class="text-base">📖</span>
                        <span>Buku Servis: {{ $car->has_service_book ? 'Tersedia' : 'Tidak Ada' }}</span>
                    </div>
                </div>

            </div>

            <!-- Detail Pajak & Legalitas Kendaraan -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                <h3 class="text-lg font-extrabold text-[#0f1e36] tracking-tight mb-4">
                    Status Pajak & Legalitas Surat
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Pajak Tahunan</span>
                        <span class="text-sm font-extrabold {{ $car->tax_status === 'active' ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $car->tax_status === 'active' ? 'Hidup (s/d ' . ($car->tax_expiry_date ? $car->tax_expiry_date->format('M Y') : '-') . ')' : 'Pajak Telat / Mati' }}
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Plat Kaleng (5 Tahunan)</span>
                        <span class="text-sm font-extrabold text-slate-800">
                            Berlaku s/d {{ $car->plate_expiry_year ?? '-' }}
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Plat & Daerah</span>
                        <span class="text-sm font-extrabold text-slate-800">
                            Plat {{ $car->plate_region }} ({{ ucfirst($car->plate_type ?? 'Hitam/Putih') }})
                        </span>
                    </div>

                    @if($car->annual_tax_amount)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 sm:col-span-3 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Estimasi Pajak PKB per Tahun</span>
                                <span class="text-sm font-extrabold text-slate-800">
                                    ± Rp {{ number_format($car->annual_tax_amount, 0, ',', '.') }} / tahun
                                </span>
                            </div>
                            <span class="text-xs text-slate-400 italic">Berdasarkan info STNK</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Spesifikasi Teknis Kendaraan -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                <h3 class="text-lg font-extrabold text-[#0f1e36] tracking-tight mb-5">
                    Spesifikasi Teknis
                </h3>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 text-sm">
                    <div class="p-3.5 rounded-2xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Odometer (KM)</span>
                        <span class="font-extrabold text-[#0f1e36] text-base">{{ number_format($car->mileage_km, 0, ',', '.') }} km</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Transmisi</span>
                        <span class="font-extrabold text-[#0f1e36] text-base capitalize">{{ $car->transmission }}</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Bahan Bakar</span>
                        <span class="font-extrabold text-[#0f1e36] text-base capitalize">{{ $car->fuel_type }}</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Kapasitas Mesin</span>
                        <span class="font-extrabold text-[#0f1e36] text-base">{{ number_format($car->engine_cc, 0, ',', '.') }} cc</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Warna Bodi</span>
                        <span class="font-extrabold text-[#0f1e36] text-base capitalize">{{ $car->color }}</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Kapasitas Penumpang</span>
                        <span class="font-extrabold text-[#0f1e36] text-base">{{ $car->seats }} Kursi</span>
                    </div>
                </div>
            </div>

            <!-- Fitur-Fitur Kendaraan -->
            @if($car->features->isNotEmpty())
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                    <h3 class="text-lg font-extrabold text-[#0f1e36] tracking-tight mb-4">
                        Kelengkapan & Fitur Tambahan
                    </h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($car->features as $feature)
                            <div class="flex items-center gap-2.5 text-xs font-bold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                                <span class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                                <span>{{ $feature->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Deskripsi Penjual -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                <h3 class="text-lg font-extrabold text-[#0f1e36] tracking-tight mb-3">
                    Catatan & Deskripsi dari Pemilik
                </h3>
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line font-medium bg-slate-50/60 p-5 rounded-2xl border border-slate-100">
                    {{ $car->description }}
                </div>
            </div>

        </div>

        <!-- Right Column: Sticky Seller Contact Widget -->
        <div class="lg:col-span-1 lg:sticky lg:top-24 space-y-6">
            
            <!-- Seller Box -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-sm">
                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block mb-4">
                    Informasi Penjual
                </span>
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-[#0f1e36] text-white flex items-center justify-center font-extrabold text-xl shadow-sm">
                        {{ strtoupper(substr($car->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-extrabold text-[#0f1e36] text-base leading-tight">{{ $car->user->name }}</h4>
                        <p class="text-xs font-semibold text-slate-600 mt-1 flex items-center gap-1">
                            <span>📍</span> {{ $car->city->name }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Bergabung {{ $car->user->created_at->format('M Y') }}
                        </p>
                    </div>
                </div>

                <!-- Tombol Kontak WhatsApp Direct -->
                @if($car->status === 'active' && $car->whats_app_url)
                    <a href="{{ $car->whats_app_url }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="w-full py-4 px-6 rounded-2xl bg-[#25D366] hover:bg-[#20ba59] text-white font-extrabold text-sm sm:text-base flex items-center justify-center gap-2.5 shadow hover:shadow-md transition active:scale-95 mb-2">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        Hubungi via WhatsApp
                    </a>

                    <p class="text-center text-xs text-slate-400 mt-2">
                        Pesan otomatis akan disiapkan saat membuka chat WhatsApp.
                    </p>
                @else
                    <div class="p-4 rounded-2xl bg-slate-100 text-center text-xs font-bold text-slate-600 mb-2">
                        Unit ini sudah tidak menerima pesan baru (Sold/Inactive)
                    </div>
                @endif
            </div>

            <!-- Safety Guidelines Box -->
            <div class="bg-amber-50/80 rounded-3xl p-6 border border-amber-200/80 text-xs">
                <h5 class="font-extrabold text-amber-950 mb-2 flex items-center gap-1.5 text-sm">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Panduan Transaksi Aman P2P:
                </h5>
                <ul class="text-amber-900 space-y-1.5 list-disc list-inside leading-relaxed font-medium">
                    <li>Jangan transfer DP / tanda jadi sebelum bertemu dan cek fisik unit.</li>
                    <li>Janjian di tempat terbuka & ramai atau area publik yang aman.</li>
                    <li>Periksa keaslian dokumen BPKB, STNK, dan kesesuaian nomor rangka/mesin.</li>
                </ul>
            </div>

        </div>

    </div>

    <!-- Related Cars Section -->
    @if(isset($relatedCars) && $relatedCars->isNotEmpty())
        <div class="border-t border-slate-200 pt-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-extrabold text-[#0f1e36] tracking-tight">
                        Mobil Serupa yang Mungkin Anda Sukai
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Pilihan lain dari merek {{ $car->carModel->brand->name }} dan sekitarnya.
                    </p>
                </div>
                <a href="{{ route('cars.index', ['brand' => $car->carModel->brand->slug]) }}" 
                   class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                    Lihat Semua {{ $car->carModel->brand->name }} &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedCars as $relCar)
                    <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                        <div>
                            <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                                <a href="{{ route('cars.show', $relCar->slug) }}">
                                    <img src="{{ $relCar->primaryImage ? $relCar->primaryImage->path : 'https://placehold.co/800x500?text=Mobil+Bekas' }}" 
                                         alt="{{ $relCar->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out">
                                </a>
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-black/60 text-white font-bold text-xs">
                                    {{ $relCar->manufacture_year }}
                                </span>
                            </div>

                            <div class="p-5 pb-3">
                                <h4 class="font-extrabold text-[#0f1e36] text-base group-hover:text-blue-600 transition line-clamp-1 mb-1">
                                    <a href="{{ route('cars.show', $relCar->slug) }}">
                                        {{ $relCar->title }}
                                    </a>
                                </h4>
                                <div class="text-lg font-black text-[#0f1e36] mb-2">
                                    Rp {{ number_format($relCar->price, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-slate-500 font-semibold flex items-center gap-1.5">
                                    <span>{{ number_format($relCar->mileage_km, 0, ',', '.') }} km</span>
                                    <span>•</span>
                                    <span class="capitalize">{{ $relCar->transmission }}</span>
                                    <span>•</span>
                                    <span>{{ $relCar->city->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-medium">{{ $relCar->city->name }}</span>
                            <a href="{{ route('cars.show', $relCar->slug) }}" class="text-xs font-bold text-[#0f1e36] hover:text-blue-600">
                                Detail &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
