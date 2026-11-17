@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('cars.index') }}" class="hover:text-slate-800 transition">Browse Cars</a>
        <span>/</span>
        <a href="{{ route('cars.index', ['brand' => $car->carModel->brand->slug]) }}" class="hover:text-slate-800 transition">{{ $car->carModel->brand->name }}</a>
        <span>/</span>
        <span class="text-slate-800 font-bold truncate max-w-xs">{{ $car->title }}</span>
    </nav>

    <!-- Main Grid: Left (Photos & Details) + Right (Sticky Seller Box) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left Column: 2 Spans -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Photo Gallery Card -->
            <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-sm">
                <!-- Main Big Photo -->
                <div class="aspect-[16/10] sm:aspect-[16/9] rounded-2xl overflow-hidden bg-slate-900 relative">
                    <img id="mainCarImage" 
                         src="{{ $car->primaryImage ? $car->primaryImage->path : 'https://placehold.co/1200x800?text=Mobil+Bekas' }}" 
                         alt="{{ $car->title }}" 
                         class="w-full h-full object-cover">
                    
                    @if($car->status === 'sold')
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                            <span class="px-5 py-2 rounded-xl bg-red-600 text-white font-extrabold text-base tracking-wider uppercase">
                                UNIT SUDAH TERJUAL (SOLD)
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Thumbnails Gallery -->
                @if($car->images->count() > 1)
                    <div class="flex items-center gap-3 overflow-x-auto pt-4 scrollbar-none">
                        @foreach($car->images as $img)
                            <button type="button" 
                                    onclick="document.getElementById('mainCarImage').src = '{{ $img->path }}'"
                                    class="w-20 h-16 rounded-xl overflow-hidden border-2 border-transparent hover:border-[#0f1e36] focus:border-[#0f1e36] transition shrink-0">
                                <img src="{{ $img->path }}" alt="Thumbnail" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Title & Pricing Block -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                        {{ $car->carModel->brand->name }} • NIK {{ $car->manufacture_year }}
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold text-blue-700 bg-blue-50">
                        Pemakaian {{ $car->registration_year }}
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $car->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                        {{ strtoupper($car->status) }}
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0f1e36] tracking-tight mb-4">
                    {{ $car->title }}
                </h1>

                <div class="flex flex-col sm:flex-row sm:items-baseline gap-2 pb-6 border-b border-slate-100">
                    <span class="text-3xl sm:text-4xl font-black text-[#0f1e36] tracking-tight">
                        Rp {{ number_format($car->price, 0, ',', '.') }}
                    </span>
                    @if($car->price_negotiable)
                        <span class="text-sm font-bold text-slate-500">(Bisa Nego Santai)</span>
                    @else
                        <span class="text-sm font-bold text-slate-500">(Harga Pas / Nett)</span>
                    @endif
                </div>

                <!-- Trust Badges Checklist (Hijau) -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-6">
                    <div class="flex items-center gap-2 text-xs font-bold text-emerald-800 bg-emerald-50/80 px-3 py-2.5 rounded-xl border border-emerald-200/60">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>BPKB: {{ $car->bpkb_status === 'on_hand' ? 'On Hand (Ready)' : 'Di Leasing' }}</span>
                    </div>

                    <div class="flex items-center gap-2 text-xs font-bold text-emerald-800 bg-emerald-50/80 px-3 py-2.5 rounded-xl border border-emerald-200/60">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $car->is_accident_free ? 'Bebas Tabrakan' : 'Pernah Insiden' }}</span>
                    </div>

                    <div class="flex items-center gap-2 text-xs font-bold text-emerald-800 bg-emerald-50/80 px-3 py-2.5 rounded-xl border border-emerald-200/60">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ $car->is_flood_free ? 'Bebas Banjir' : 'Kena Banjir' }}</span>
                    </div>

                    <div class="flex items-center gap-2 text-xs font-bold text-slate-700 bg-slate-50 px-3 py-2.5 rounded-xl border border-slate-200">
                        <span>👤 Tangan ke-{{ $car->owner_count }} ({{ ucfirst($car->registration_type) }})</span>
                    </div>

                    <div class="flex items-center gap-2 text-xs font-bold text-slate-700 bg-slate-50 px-3 py-2.5 rounded-xl border border-slate-200">
                        <span>🔑 Kunci Serep: {{ $car->has_spare_key ? 'Ada' : 'Tidak Ada' }}</span>
                    </div>

                    <div class="flex items-center gap-2 text-xs font-bold text-slate-700 bg-slate-50 px-3 py-2.5 rounded-xl border border-slate-200">
                        <span>📖 Buku Servis: {{ $car->has_service_book ? 'Lengkap' : 'Tidak Ada' }}</span>
                    </div>
                </div>
            </div>

            <!-- Detail Pajak & Legalitas Kendaraan -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                <h3 class="text-lg font-bold text-[#0f1e36] mb-4">Status Pajak & Plat Nomor</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Pajak Tahunan</span>
                        <span class="text-sm font-extrabold {{ $car->tax_status === 'active' ? 'text-emerald-700' : 'text-red-700' }}">
                            {{ $car->tax_status === 'active' ? 'Hidup (s/d ' . ($car->tax_expiry_date ? $car->tax_expiry_date->format('M Y') : '-') . ')' : 'Pajak Mati' }}
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Plat Kaleng (5 Tahunan)</span>
                        <span class="text-sm font-extrabold text-slate-800">
                            Berlaku s/d {{ $car->plate_expiry_year ?? '-' }}
                        </span>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Plat & Wilayah</span>
                        <span class="text-sm font-extrabold text-slate-800">
                            Plat {{ $car->plate_region }} ({{ ucfirst($car->plate_type ?? 'Standar') }})
                        </span>
                    </div>

                    @if($car->annual_tax_amount)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 sm:col-span-2 md:col-span-3">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Estimasi Biaya Pajak (PKB)</span>
                            <span class="text-sm font-extrabold text-slate-800">
                                ~Rp {{ number_format($car->annual_tax_amount, 0, ',', '.') }} / tahun
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Spesifikasi Teknis Kendaraan -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                <h3 class="text-lg font-bold text-[#0f1e36] mb-4">Spesifikasi Kendaraan</h3>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 text-sm">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Jarak Tempuh (KM)</span>
                        <span class="font-extrabold text-slate-900 text-base">{{ number_format($car->mileage_km, 0, ',', '.') }} km</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Transmisi</span>
                        <span class="font-extrabold text-slate-900 text-base capitalize">{{ $car->transmission }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Bahan Bakar</span>
                        <span class="font-extrabold text-slate-900 text-base capitalize">{{ $car->fuel_type }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Kapasitas Mesin</span>
                        <span class="font-extrabold text-slate-900 text-base">{{ number_format($car->engine_cc, 0, ',', '.') }} cc</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Warna Bodi</span>
                        <span class="font-extrabold text-slate-900 text-base">{{ $car->color }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Kapasitas Kursi</span>
                        <span class="font-extrabold text-slate-900 text-base">{{ $car->seats }} Penumpang</span>
                    </div>
                </div>
            </div>

            <!-- Fitur-Fitur Kendaraan -->
            @if($car->features->isNotEmpty())
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                    <h3 class="text-lg font-bold text-[#0f1e36] mb-4">Kelengkapan Fitur</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($car->features as $feature)
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-700 bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                <span>{{ $feature->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Deskripsi Penjual -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
                <h3 class="text-lg font-bold text-[#0f1e36] mb-3">Deskripsi Lengkap dari Pemilik</h3>
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line font-medium">
                    {{ $car->description }}
                </div>
            </div>

        </div>

        <!-- Right Column: Sticky Seller Contact Widget -->
        <div class="lg:col-span-1 lg:sticky lg:top-24 space-y-6">
            
            <!-- Seller Box -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm">
                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block mb-4">Informasi Pemilik Unit</span>
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-slate-100 text-[#0f1e36] flex items-center justify-center font-extrabold text-xl border border-slate-200">
                        {{ substr($car->user->name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-[#0f1e36] text-base">{{ $car->user->name }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">📍 {{ $car->city->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Anggota sejak {{ $car->user->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <!-- Tombol Kontak WhatsApp Direct -->
                @if($car->status === 'active' && $car->whats_app_url)
                    <a href="{{ $car->whats_app_url }}" target="_blank" rel="noopener noreferrer" 
                       class="w-full py-4 px-6 rounded-2xl bg-[#25D366] hover:bg-[#20ba59] text-white font-extrabold text-sm sm:text-base flex items-center justify-center gap-2 shadow hover:shadow-md transition active:scale-95 mb-3">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        Hubungi via WhatsApp
                    </a>
                    <p class="text-center text-xs text-slate-400">
                        Chat langsung dengan nomor {{ $car->user->phone }}
                    </p>
                @else
                    <div class="p-4 rounded-xl bg-slate-100 text-center text-xs font-bold text-slate-600 mb-2">
                        Unit ini sudah tidak menerima pesan baru (Sold/Inactive)
                    </div>
                @endif
            </div>

            <!-- Safety Guidelines Box -->
            <div class="bg-amber-50/70 rounded-3xl p-6 border border-amber-200/70 text-xs">
                <h5 class="font-extrabold text-amber-900 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Tips Transaksi Aman COD:
                </h5>
                <ul class="text-amber-800 space-y-1.5 list-disc list-inside leading-relaxed">
                    <li>Jangan transfer DP sebelum cek fisik unit & surat asli.</li>
                    <li>Janjian bertemu di tempat publik yang aman.</li>
                    <li>Bawa mekanik/inspektur independen jika diperlukan.</li>
                </ul>
            </div>

        </div>

    </div>

</div>
@endsection
