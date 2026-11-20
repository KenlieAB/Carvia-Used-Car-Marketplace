@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12"
     x-data="{
         brands: {{ json_encode($brands) }},
         selectedBrandId: '{{ old('brand_id', '') }}',
         selectedModelId: '{{ old('car_model_id', '') }}',
         filteredModels: [],
         imagePreviews: [],

         init() {
             if (this.selectedBrandId) {
                 this.updateModels();
             }
         },

         updateModels() {
             const brand = this.brands.find(b => b.id == this.selectedBrandId);
             this.filteredModels = brand ? brand.car_models : [];
             if (!this.filteredModels.some(m => m.id == this.selectedModelId)) {
                 this.selectedModelId = '';
             }
         },

         handleFileSelect(event) {
             const files = Array.from(event.target.files);
             this.imagePreviews = [];
             files.forEach((file, index) => {
                 if (file.type.startsWith('image/')) {
                     const reader = new FileReader();
                     reader.onload = (e) => {
                         this.imagePreviews.push({
                             url: e.target.result,
                             name: file.name,
                             isPrimary: index === 0
                         });
                     };
                     reader.readAsDataURL(file);
                 }
             });
         }
     }">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('cars.index') }}" class="hover:text-slate-800 transition">Katalog Mobil</a>
        <span>/</span>
        <span class="text-[#0f1e36] font-bold">Pasang Iklan Mobil</span>
    </nav>

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <span class="inline-block text-xs font-extrabold tracking-widest uppercase text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                GRATIS & TANPA PERANTARA
            </span>
            <span class="inline-block text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                Langsung Tayang (Post-Moderation)
            </span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f1e36] tracking-tight">
            Pasang Iklan Mobil Anda
        </h1>
        <p class="text-slate-500 mt-1.5 text-sm sm:text-base">
            Isi rincian kendaraan dengan lengkap dan jujur agar calon pembeli dapat langsung menghubungi Anda via WhatsApp.
        </p>
    </div>

    <!-- Alert Global Validation Errors -->
    @if ($errors->any())
        <div class="mb-8 p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
            <div class="flex items-center gap-2 font-bold mb-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>Mohon periksa kembali input formulir Anda:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <!-- =================== KELOMPOK 1: IDENTITAS MOBIL =================== -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">1</span>
                    Identitas & Model Kendaraan
                </h2>
                <p class="text-xs text-slate-500 mt-1">Pilih merek dan model resmi kendaraan Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Brand -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Merek Mobil <span class="text-rose-500">*</span>
                    </label>
                    <select name="brand_id" 
                            x-model="selectedBrandId" 
                            @change="updateModels()" 
                            required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="">-- Pilih Merek --</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Model (Dependent Dropdown) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Model Mobil <span class="text-rose-500">*</span>
                    </label>
                    <select name="car_model_id" 
                            x-model="selectedModelId" 
                            required
                            :disabled="!selectedBrandId"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36] disabled:bg-slate-100 disabled:text-slate-400">
                        <option value="">-- Pilih Model --</option>
                        <template x-for="model in filteredModels" :key="model.id">
                            <option :value="model.id" x-text="model.name + ' (' + (model.body_type ? model.body_type.toUpperCase() : '') + ')'"></option>
                        </template>
                    </select>
                </div>
            </div>

            <!-- Judul Iklan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Judul Iklan <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       value="{{ old('title') }}" 
                       required
                       placeholder="Contoh: Toyota Kijang Innova Reborn 2.4 V Diesel AT 2018 Hitam Tangan Pertama"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                <p class="text-[11px] text-slate-400 mt-1.5">Sertakan merek, model, varian, transmisi, tahun, dan warna agar mudah ditemukan.</p>
            </div>

            <!-- Lokasi Kota -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Lokasi Mobil / Kota <span class="text-rose-500">*</span>
                </label>
                <select name="city_id" 
                        required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                    <option value="">-- Pilih Kota Lokasi Unit --</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- =================== KELOMPOK 2: SPESIFIKASI & MESIN =================== -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">2</span>
                    Spesifikasi Mesin & Teknis
                </h2>
                <p class="text-xs text-slate-500 mt-1">Detail teknis untuk gambaran performa kendaraan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <!-- NIK Tahun Pembuatan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tahun Perakitan (NIK) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           name="manufacture_year" 
                           value="{{ old('manufacture_year', date('Y')) }}" 
                           min="1990" 
                           max="{{ date('Y') + 1 }}" 
                           required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Tahun Registrasi / STNK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tahun Pemakaian (STNK) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           name="registration_year" 
                           value="{{ old('registration_year', date('Y')) }}" 
                           min="1990" 
                           max="{{ date('Y') + 1 }}" 
                           required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Transmisi -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Transmisi <span class="text-rose-500">*</span>
                    </label>
                    <select name="transmission" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="automatic" {{ old('transmission') === 'automatic' ? 'selected' : '' }}>Otomatis (Automatic)</option>
                        <option value="manual" {{ old('transmission') === 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>

                <!-- Bahan Bakar -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Bahan Bakar <span class="text-rose-500">*</span>
                    </label>
                    <select name="fuel_type" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="petrol" {{ old('fuel_type') === 'petrol' ? 'selected' : '' }}>Bensin (Petrol)</option>
                        <option value="diesel" {{ old('fuel_type') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="hybrid" {{ old('fuel_type') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="electric" {{ old('fuel_type') === 'electric' ? 'selected' : '' }}>Listrik (Electric)</option>
                    </select>
                </div>

                <!-- Kapasitas Mesin CC -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kapasitas Mesin (CC) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           name="engine_cc" 
                           value="{{ old('engine_cc', 1500) }}" 
                           placeholder="Contoh: 1500, 2000, 2400"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Warna Bodi -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Warna Bodi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="color" 
                           value="{{ old('color') }}" 
                           placeholder="Contoh: Hitam Metalik, Putih Mutiara"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Kapasitas Tempat Duduk -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kapasitas Penumpang <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           name="seats" 
                           value="{{ old('seats', 5) }}" 
                           min="2" max="15"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Jarak Tempuh KM -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Odometer / KM <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           name="mileage_km" 
                           value="{{ old('mileage_km') }}" 
                           placeholder="Contoh: 45000"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Riwayat Servis -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Riwayat Servis <span class="text-rose-500">*</span>
                    </label>
                    <select name="service_record" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="authorized_dealer" {{ old('service_record') === 'authorized_dealer' ? 'selected' : '' }}>Rutin Bengkel Resmi</option>
                        <option value="specialist" {{ old('service_record') === 'specialist' ? 'selected' : '' }}>Bengkel Spesialis Terpercaya</option>
                        <option value="self" {{ old('service_record') === 'self' ? 'selected' : '' }}>Servis Mandiri</option>
                        <option value="none" {{ old('service_record') === 'none' ? 'selected' : '' }}>Tidak Tercatat</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- =================== KELOMPOK 3: HARGA & LEGALITAS SURAT =================== -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">3</span>
                    Harga & Legalitas Dokumen
                </h2>
                <p class="text-xs text-slate-500 mt-1">Transparansi dokumen membuat iklan Anda lebih terpercaya.</p>
            </div>

            <!-- Harga & Nego -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Harga Jual (Rupiah) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold text-sm">Rp</span>
                        <input type="number" 
                               name="price" 
                               value="{{ old('price') }}" 
                               placeholder="Contoh: 285000000"
                               required
                               class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 text-sm font-bold text-[#0f1e36] focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                    </div>
                </div>

                <div class="pb-3">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="price_negotiable" value="1" {{ old('price_negotiable', true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded text-[#0f1e36] focus:ring-[#0f1e36] border-slate-300">
                        <span class="text-sm font-bold text-slate-700">Harga Masih Bisa Nego Santai</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 pt-2">
                <!-- Status BPKB -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Status BPKB <span class="text-rose-500">*</span>
                    </label>
                    <select name="bpkb_status" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="on_hand" {{ old('bpkb_status') === 'on_hand' ? 'selected' : '' }}>On Hand (Ready di Rumah)</option>
                        <option value="in_leasing" {{ old('bpkb_status') === 'in_leasing' ? 'selected' : '' }}>Masih di Leasing / Kredit</option>
                    </select>
                </div>

                <!-- Status STNK -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Status STNK <span class="text-rose-500">*</span>
                    </label>
                    <select name="stnk_status" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="available" {{ old('stnk_status') === 'available' ? 'selected' : '' }}>Ada (Asli)</option>
                        <option value="lost" {{ old('stnk_status') === 'lost' ? 'selected' : '' }}>Hilang (Ada Surat Kehilangan)</option>
                    </select>
                </div>

                <!-- Tangan ke- -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kepemilikan (Tangan ke-) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           name="owner_count" 
                           value="{{ old('owner_count', 1) }}" 
                           min="1" max="10" 
                           required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Registrasi Atas Nama -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Atas Nama STNK <span class="text-rose-500">*</span>
                    </label>
                    <select name="registration_type" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="personal" {{ old('registration_type') === 'personal' ? 'selected' : '' }}>Perorangan / Pribadi</option>
                        <option value="company" {{ old('registration_type') === 'company' ? 'selected' : '' }}>Perusahaan / PT</option>
                    </select>
                </div>

                <!-- Modifikasi -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kondisi Modifikasi <span class="text-rose-500">*</span>
                    </label>
                    <select name="modification_status" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="stock" {{ old('modification_status') === 'stock' ? 'selected' : '' }}>Standar Total (Original)</option>
                        <option value="light_mod" {{ old('modification_status') === 'light_mod' ? 'selected' : '' }}>Modifikasi Ringan (Velg/Audio)</option>
                        <option value="full_mod" {{ old('modification_status') === 'full_mod' ? 'selected' : '' }}>Modifikasi Penuh</option>
                    </select>
                </div>
            </div>

            <!-- Checkbox Dokumen Pendukung & Trust Badges -->
            <div class="pt-4 border-t border-slate-100">
                <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Kelengkapan Dokumen & Kondisi Utama</span>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" name="has_service_book" value="1" {{ old('has_service_book') ? 'checked' : '' }}
                               class="rounded text-[#0f1e36] focus:ring-[#0f1e36]">
                        <span class="text-xs font-semibold text-slate-700">📖 Buku Servis Ada</span>
                    </label>

                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" name="has_spare_key" value="1" {{ old('has_spare_key') ? 'checked' : '' }}
                               class="rounded text-[#0f1e36] focus:ring-[#0f1e36]">
                        <span class="text-xs font-semibold text-slate-700">🔑 Kunci Serep Ada</span>
                    </label>

                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" name="has_faktur" value="1" {{ old('has_faktur', true) ? 'checked' : '' }}
                               class="rounded text-[#0f1e36] focus:ring-[#0f1e36]">
                        <span class="text-xs font-semibold text-slate-700">📄 Faktur Pembelian Ada</span>
                    </label>

                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" name="has_form_a" value="1" {{ old('has_form_a') ? 'checked' : '' }}
                               class="rounded text-[#0f1e36] focus:ring-[#0f1e36]">
                        <span class="text-xs font-semibold text-slate-700">📑 Form A (Khusus CBU)</span>
                    </label>

                    <label class="flex items-center gap-2 p-3 rounded-xl border border-emerald-200 bg-emerald-50/50 hover:bg-emerald-50 cursor-pointer">
                        <input type="checkbox" name="is_accident_free" value="1" {{ old('is_accident_free', true) ? 'checked' : '' }}
                               class="rounded text-emerald-600 focus:ring-emerald-600">
                        <span class="text-xs font-bold text-emerald-900">🛡️ Bebas Tabrakan Besar</span>
                    </label>

                    <label class="flex items-center gap-2 p-3 rounded-xl border border-emerald-200 bg-emerald-50/50 hover:bg-emerald-50 cursor-pointer">
                        <input type="checkbox" name="is_flood_free" value="1" {{ old('is_flood_free', true) ? 'checked' : '' }}
                               class="rounded text-emerald-600 focus:ring-emerald-600">
                        <span class="text-xs font-bold text-emerald-900">🌊 Bebas Rendam Banjir</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- =================== KELOMPOK 4: PAJAK & PLAT NOMOR =================== -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">4</span>
                    Pajak & Informasi Plat Nomor
                </h2>
                <p class="text-xs text-slate-500 mt-1">Status keabsahan pajak kendaraan bermotor (PKB).</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <!-- Kode Plat Daerah -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Plat Daerah (Huruf Depan) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="plate_region" 
                           value="{{ old('plate_region') }}" 
                           placeholder="Contoh: B, D, L, F, AD"
                           maxlength="5"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-bold text-slate-800 uppercase focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Ganjil / Genap -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tipe Plat (Ganjil / Genap)
                    </label>
                    <select name="plate_type" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="">-- Pilih Jika Relevan --</option>
                        <option value="odd" {{ old('plate_type') === 'odd' ? 'selected' : '' }}>Ganjil</option>
                        <option value="even" {{ old('plate_type') === 'even' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>

                <!-- Status Pajak Tahunan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Status Pajak Tahunan <span class="text-rose-500">*</span>
                    </label>
                    <select name="tax_status" required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="active" {{ old('tax_status') === 'active' ? 'selected' : '' }}>Hidup / Aktif</option>
                        <option value="expired" {{ old('tax_status') === 'expired' ? 'selected' : '' }}>Mati / Telat</option>
                    </select>
                </div>

                <!-- Tanggal Jatuh Tempo Pajak -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Jatuh Tempo Pajak Tahunan
                    </label>
                    <input type="date" 
                           name="tax_expiry_date" 
                           value="{{ old('tax_expiry_date') }}" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Masa Plat Kaleng (5 Tahunan) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Masa Berlaku Plat (Tahun)
                    </label>
                    <input type="number" 
                           name="plate_expiry_year" 
                           value="{{ old('plate_expiry_year', date('Y') + 3) }}" 
                           min="{{ date('Y') }}" 
                           max="{{ date('Y') + 10 }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>

                <!-- Estimasi Biaya PKB -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Estimasi Pajak PKB per Tahun (Rp)
                    </label>
                    <input type="number" 
                           name="annual_tax_amount" 
                           value="{{ old('annual_tax_amount') }}" 
                           placeholder="Contoh: 3500000"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>
            </div>
        </div>

        <!-- =================== KELOMPOK 5: FITUR & DESKRIPSI =================== -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">5</span>
                    Fitur Kendaraan & Catatan Penjual
                </h2>
                <p class="text-xs text-slate-500 mt-1">Centang fitur yang ada pada mobil dan tuliskan catatan lengkap.</p>
            </div>

            <!-- Features Checkbox -->
            @if(isset($features) && $features->isNotEmpty())
                <div>
                    <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Pilihan Fitur Bawaan & Tambahan</span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                        @foreach($features as $feature)
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer text-xs font-medium text-slate-700">
                                <input type="checkbox" name="features[]" value="{{ $feature->id }}" 
                                       {{ in_array($feature->id, old('features', [])) ? 'checked' : '' }}
                                       class="rounded text-[#0f1e36] focus:ring-[#0f1e36]">
                                <span>{{ $feature->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Deskripsi Lengkap -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Deskripsi Lengkap Kondisi Kendaraan <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" rows="6" required 
                          placeholder="Jelaskan kondisi mesin, bodi/cat, interior, pemakaian sehari-hari, riwayat servis, ban, AC dingin, dan alasan dijual..."
                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">{{ old('description') }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1.5">Minimal 20 karakter. Deskripsi yang jelas mempermudah negosiasi dengan pembeli.</p>
            </div>
        </div>

        <!-- =================== KELOMPOK 6: UPLOAD FOTO =================== -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">6</span>
                    Foto Kendaraan (Minimal 1 Foto)
                </h2>
                <p class="text-xs text-slate-500 mt-1">Unggah foto eksterior depan, samping, belakang, interior, dan odometer.</p>
            </div>

            <!-- Upload Input Dropzone -->
            <div>
                <label class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-slate-300 rounded-3xl cursor-pointer hover:border-[#0f1e36] hover:bg-slate-50/50 transition p-6 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-[#0f1e36] flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-extrabold text-[#0f1e36]">Klik untuk Unggah Foto Mobil</span>
                    <span class="text-xs text-slate-400 mt-1">Mendukung format JPG, PNG, WEBP (Maksimal 10 foto, masing-masing max 5MB)</span>
                    <input type="file" 
                           name="images[]" 
                           multiple 
                           accept="image/*"
                           required
                           @change="handleFileSelect($event)"
                           class="hidden">
                </label>
                <p class="text-[11px] text-slate-500 mt-2">
                    💡 <strong>Tips:</strong> Foto pertama yang Anda pilih akan menjadi <strong>foto cover utama</strong> iklan.
                </p>
            </div>

            <!-- Image Previews Container -->
            <template x-if="imagePreviews.length > 0">
                <div>
                    <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                        Preview Foto Terpilih (<span x-text="imagePreviews.length"></span> Foto)
                    </span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                        <template x-for="(preview, index) in imagePreviews" :key="index">
                            <div class="aspect-[4/3] rounded-2xl overflow-hidden relative border border-slate-200 bg-slate-100 group shadow-sm">
                                <img :src="preview.url" :alt="preview.name" class="w-full h-full object-cover">
                                <template x-if="index === 0">
                                    <span class="absolute top-2 left-2 bg-[#0f1e36] text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow">
                                        FOTO UTAMA
                                    </span>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- =================== SUBMIT ACTION BAR =================== -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h4 class="font-bold text-[#0f1e36] text-sm">Sudah memeriksa kelengkapan data?</h4>
                <p class="text-xs text-slate-500 mt-0.5">Iklan Anda akan langsung tayang dan dapat dihubungi calon pembeli.</p>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('cars.index') }}" 
                   class="px-6 py-3.5 rounded-2xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition text-center w-full sm:w-auto">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3.5 rounded-2xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white text-sm font-extrabold shadow hover:shadow-md transition active:scale-95 text-center w-full sm:w-auto shrink-0">
                    Terbitkan Iklan Sekarang &rarr;
                </button>
            </div>
        </div>

    </form>
</div>
@endsection
