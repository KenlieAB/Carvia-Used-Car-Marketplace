@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12"
     x-data="{
         brands: {{ json_encode($brands) }},
         selectedBrandId: '{{ old('brand_id', $car->carModel->brand_id ?? '') }}',
         selectedModelId: '{{ old('car_model_id', $car->car_model_id) }}',
         filteredModels: [],

         imagePreviews: [],
         rawFiles: [],
         deleteImageIds: [],

         // Formatted number inputs
         rawPrice: '{{ old('price', $car->price) }}',
         displayPrice: '',
         rawMileage: '{{ old('mileage_km', $car->mileage_km) }}',
         displayMileage: '',
         rawTax: '{{ old('annual_tax_amount', $car->annual_tax_amount) }}',
         displayTax: '',

         init() {
             if (this.selectedBrandId) {
                 this.updateModels();
             }
             if (this.rawPrice) {
                 this.displayPrice = this.formatNumber(this.rawPrice);
             }
             if (this.rawMileage) {
                 this.displayMileage = this.formatNumber(this.rawMileage);
             }
             if (this.rawTax) {
                 this.displayTax = this.formatNumber(this.rawTax);
             }
         },

         formatNumber(val) {
             if (!val) return '';
             const clean = val.toString().replace(/[^0-9]/g, '');
             return clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
         },

         onNumberInput(field, event) {
             const clean = event.target.value.replace(/[^0-9]/g, '');
             if (field === 'price') {
                 this.rawPrice = clean;
                 this.displayPrice = this.formatNumber(clean);
             } else if (field === 'mileage') {
                 this.rawMileage = clean;
                 this.displayMileage = this.formatNumber(clean);
             } else if (field === 'tax') {
                 this.rawTax = clean;
                 this.displayTax = this.formatNumber(clean);
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
             const newFiles = Array.from(event.target.files);
             if (newFiles.length === 0) return;

             if (this.rawFiles.length + newFiles.length > 10) {
                 alert('Maksimal foto yang dapat diunggah adalah 10 foto.');
                 return;
             }

             newFiles.forEach((file) => {
                 if (file.type.startsWith('image/')) {
                     this.rawFiles.push(file);
                     const reader = new FileReader();
                     reader.onload = (e) => {
                         this.imagePreviews.push({ url: e.target.result, name: file.name });
                     };
                     reader.readAsDataURL(file);
                 }
             });

             this.syncInputFiles();
         },

         removeNewImage(index) {
             this.rawFiles.splice(index, 1);
             this.imagePreviews.splice(index, 1);
             this.syncInputFiles();
         },

         markDeleteExisting(id) {
             if (!this.deleteImageIds.includes(id)) {
                 this.deleteImageIds.push(id);
             }
         },

         syncInputFiles() {
             const dt = new DataTransfer();
             this.rawFiles.forEach(file => dt.items.add(file));
             this.$refs.fileInput.files = dt.files;
         }
     }">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-slate-800 transition">Beranda</a>
        <span>/</span>
        <a href="{{ route('my-ads') }}" class="hover:text-slate-800 transition">Garasi Saya</a>
        <span>/</span>
        <span class="text-[#0f1e36] font-bold truncate max-w-xs">Edit: {{ $car->title }}</span>
    </nav>

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <span class="inline-block text-xs font-extrabold tracking-widest uppercase text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                EDIT IKLAN
            </span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f1e36] tracking-tight">
            Perbarui Iklan Mobil
        </h1>
        <p class="text-slate-500 mt-1.5 text-sm">
            Perubahan akan langsung diterapkan setelah disimpan.
        </p>
    </div>

    {{-- Alert Validation Errors --}}
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

    <form action="{{ route('cars.update', $car->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Hidden inputs untuk foto yang akan dihapus --}}
        <template x-for="id in deleteImageIds" :key="id">
            <input type="hidden" name="delete_images[]" :value="id">
        </template>

        {{-- =================== KELOMPOK 1: IDENTITAS MOBIL =================== --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">1</span>
                    Identitas & Model Kendaraan
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Brand --}}
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

                {{-- Model (Dependent Dropdown) --}}
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

            {{-- Judul Iklan --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Judul Iklan <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $car->title) }}"
                       required
                       placeholder="Contoh: Toyota Kijang Innova Reborn 2.4 V Diesel AT 2018 Hitam Tangan Pertama"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
            </div>

            {{-- Lokasi Kota --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Lokasi Mobil / Kota <span class="text-rose-500">*</span>
                </label>
                <select name="city_id"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                    <option value="">-- Pilih Kota Lokasi Unit --</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ old('city_id', $car->city_id) == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- =================== KELOMPOK 2: SPESIFIKASI & MESIN =================== --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">2</span>
                    Spesifikasi Mesin & Teknis
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tahun Perakitan (NIK) <span class="text-rose-500">*</span></label>
                    <input type="number" name="manufacture_year" value="{{ old('manufacture_year', $car->manufacture_year) }}" min="1990" max="{{ date('Y') + 1 }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tahun Pemakaian (STNK) <span class="text-rose-500">*</span></label>
                    <input type="number" name="registration_year" value="{{ old('registration_year', $car->registration_year) }}" min="1990" max="{{ date('Y') + 1 }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Transmisi <span class="text-rose-500">*</span></label>
                    <select name="transmission" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="automatic" {{ old('transmission', $car->transmission) === 'automatic' ? 'selected' : '' }}>Otomatis (Automatic)</option>
                        <option value="manual"    {{ old('transmission', $car->transmission) === 'manual'    ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Bahan Bakar <span class="text-rose-500">*</span></label>
                    <select name="fuel_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="petrol"   {{ old('fuel_type', $car->fuel_type) === 'petrol'   ? 'selected' : '' }}>Bensin (Petrol)</option>
                        <option value="diesel"   {{ old('fuel_type', $car->fuel_type) === 'diesel'   ? 'selected' : '' }}>Diesel</option>
                        <option value="hybrid"   {{ old('fuel_type', $car->fuel_type) === 'hybrid'   ? 'selected' : '' }}>Hybrid</option>
                        <option value="electric" {{ old('fuel_type', $car->fuel_type) === 'electric' ? 'selected' : '' }}>Listrik (Electric)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kapasitas Mesin (CC) <span class="text-rose-500">*</span></label>
                    <input type="number" name="engine_cc" value="{{ old('engine_cc', $car->engine_cc) }}" placeholder="1500" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Warna Bodi <span class="text-rose-500">*</span></label>
                    <input type="text" name="color" value="{{ old('color', $car->color) }}" placeholder="Contoh: Hitam Metalik" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kapasitas Penumpang <span class="text-rose-500">*</span></label>
                    <input type="number" name="seats" value="{{ old('seats', $car->seats) }}" min="2" max="15" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Odometer / KM <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="text" inputmode="numeric" :value="displayMileage" @input="onNumberInput('mileage', $event)" placeholder="Contoh: 45.000" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <input type="hidden" name="mileage_km" :value="rawMileage">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Riwayat Servis <span class="text-rose-500">*</span></label>
                    <select name="service_record" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="authorized_dealer" {{ old('service_record', $car->service_record) === 'authorized_dealer' ? 'selected' : '' }}>Rutin Bengkel Resmi</option>
                        <option value="specialist"        {{ old('service_record', $car->service_record) === 'specialist'        ? 'selected' : '' }}>Bengkel Spesialis</option>
                        <option value="self"              {{ old('service_record', $car->service_record) === 'self'              ? 'selected' : '' }}>Servis Mandiri</option>
                        <option value="none"              {{ old('service_record', $car->service_record) === 'none'              ? 'selected' : '' }}>Tidak Tercatat</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- =================== KELOMPOK 3: HARGA & LEGALITAS SURAT =================== --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">3</span>
                    Harga & Legalitas Dokumen
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Harga Jual (Rupiah) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 font-bold text-sm">Rp</span>
                        <input type="text" inputmode="numeric" :value="displayPrice" @input="onNumberInput('price', $event)" placeholder="Contoh: 285.000.000" required class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 text-sm font-bold text-[#0f1e36] focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <input type="hidden" name="price" :value="rawPrice">
                    </div>
                </div>
                <div class="pb-3">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="price_negotiable" value="1" {{ old('price_negotiable', $car->price_negotiable) ? 'checked' : '' }} class="w-5 h-5 rounded text-[#0f1e36] focus:ring-[#0f1e36] border-slate-300">
                        <span class="text-sm font-bold text-slate-700">Harga Masih Bisa Nego Santai</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 pt-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status BPKB <span class="text-rose-500">*</span></label>
                    <select name="bpkb_status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="on_hand"    {{ old('bpkb_status', $car->bpkb_status) === 'on_hand'    ? 'selected' : '' }}>On Hand (Ready di Rumah)</option>
                        <option value="in_leasing" {{ old('bpkb_status', $car->bpkb_status) === 'in_leasing' ? 'selected' : '' }}>Masih di Leasing / Kredit</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status STNK <span class="text-rose-500">*</span></label>
                    <select name="stnk_status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="available" {{ old('stnk_status', $car->stnk_status) === 'available' ? 'selected' : '' }}>Ada (Asli)</option>
                        <option value="lost"      {{ old('stnk_status', $car->stnk_status) === 'lost'      ? 'selected' : '' }}>Hilang (Ada Surat Kehilangan)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kepemilikan (Tangan ke-) <span class="text-rose-500">*</span></label>
                    <input type="number" name="owner_count" value="{{ old('owner_count', $car->owner_count) }}" min="1" max="10" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Atas Nama STNK <span class="text-rose-500">*</span></label>
                    <select name="registration_type" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="personal" {{ old('registration_type', $car->registration_type) === 'personal' ? 'selected' : '' }}>Perorangan / Pribadi</option>
                        <option value="company"  {{ old('registration_type', $car->registration_type) === 'company'  ? 'selected' : '' }}>Perusahaan / PT</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kondisi Modifikasi <span class="text-rose-500">*</span></label>
                    <select name="modification_status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="stock"     {{ old('modification_status', $car->modification_status) === 'stock'     ? 'selected' : '' }}>Standar Total (Original)</option>
                        <option value="light_mod" {{ old('modification_status', $car->modification_status) === 'light_mod' ? 'selected' : '' }}>Modifikasi Ringan (Velg/Audio)</option>
                        <option value="full_mod"  {{ old('modification_status', $car->modification_status) === 'full_mod'  ? 'selected' : '' }}>Modifikasi Penuh</option>
                    </select>
                </div>
            </div>

            {{-- Checkbox Dokumen --}}
            <div class="pt-4 border-t border-slate-100">
                <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Kelengkapan Dokumen & Kondisi Utama</span>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach([
                        ['has_service_book',  '📖 Buku Servis Ada'],
                        ['has_spare_key',     '🔑 Kunci Serep Ada'],
                        ['has_faktur',        '📄 Faktur Pembelian Ada'],
                        ['has_form_a',        '📑 Form A (Khusus CBU)'],
                        ['is_accident_free',  '🛡️ Bebas Tabrakan Besar'],
                        ['is_flood_free',     '🌊 Bebas Rendam Banjir'],
                    ] as [$field, $label])
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition">
                            <input type="checkbox" name="{{ $field }}" value="1"
                                   {{ old($field, $car->$field) ? 'checked' : '' }}
                                   class="rounded text-[#0f1e36] focus:ring-[#0f1e36] border-slate-300">
                            <span class="text-xs font-semibold text-slate-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- =================== KELOMPOK 4: PAJAK & PLAT NOMOR =================== --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">4</span>
                    Pajak & Informasi Plat Nomor
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Plat Daerah (Huruf Depan) <span class="text-rose-500">*</span></label>
                    <input type="text" name="plate_region" value="{{ old('plate_region', $car->plate_region) }}" placeholder="B, D, L, F" maxlength="5" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-bold text-slate-800 uppercase focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tipe Plat (Ganjil / Genap)</label>
                    <select name="plate_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="">-- Pilih Jika Relevan --</option>
                        <option value="odd"  {{ old('plate_type', $car->plate_type) === 'odd'  ? 'selected' : '' }}>Ganjil</option>
                        <option value="even" {{ old('plate_type', $car->plate_type) === 'even' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Pajak Tahunan <span class="text-rose-500">*</span></label>
                    <select name="tax_status" required class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <option value="active"  {{ old('tax_status', $car->tax_status) === 'active'  ? 'selected' : '' }}>Hidup / Aktif</option>
                        <option value="expired" {{ old('tax_status', $car->tax_status) === 'expired' ? 'selected' : '' }}>Mati / Telat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jatuh Tempo Pajak Tahunan</label>
                    <input type="date" name="tax_expiry_date" value="{{ old('tax_expiry_date', $car->tax_expiry_date?->format('Y-m-d')) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Masa Berlaku Plat (Tahun)</label>
                    <input type="number" name="plate_expiry_year" value="{{ old('plate_expiry_year', $car->plate_expiry_year) }}" min="{{ date('Y') }}" max="{{ date('Y') + 10 }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Estimasi Pajak PKB per Tahun (Rp)</label>
                    <div class="relative">
                        <input type="text" inputmode="numeric" :value="displayTax" @input="onNumberInput('tax', $event)" placeholder="Contoh: 3.500.000" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                        <input type="hidden" name="annual_tax_amount" :value="rawTax">
                    </div>
                </div>
            </div>
        </div>

        {{-- =================== KELOMPOK 5: FITUR & DESKRIPSI =================== --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">5</span>
                    Fitur Kendaraan & Catatan Penjual
                </h2>
            </div>

            @if(isset($features) && $features->isNotEmpty())
                <div>
                    <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Pilihan Fitur Bawaan & Tambahan</span>
                    @php $carFeatureIds = $car->features->pluck('id')->toArray(); @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                        @foreach($features as $feature)
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer text-xs font-medium text-slate-700">
                                <input type="checkbox" name="features[]" value="{{ $feature->id }}"
                                       {{ in_array($feature->id, old('features', $carFeatureIds)) ? 'checked' : '' }}
                                       class="rounded text-[#0f1e36] focus:ring-[#0f1e36]">
                                <span>{{ $feature->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Deskripsi Lengkap Kondisi Kendaraan <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" rows="6" required
                          placeholder="Jelaskan kondisi mesin, bodi/cat, interior, pemakaian sehari-hari, riwayat servis, ban, AC dingin, dan alasan dijual..."
                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">{{ old('description', $car->description) }}</textarea>
            </div>
        </div>

        {{-- =================== KELOMPOK 6: FOTO KENDARAAN =================== --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-extrabold text-[#0f1e36] tracking-tight flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black">6</span>
                    Foto Kendaraan
                </h2>
                <p class="text-xs text-slate-500 mt-1">Foto yang sudah ada dapat dihapus. Anda juga bisa menambahkan foto baru (maks. 10 total).</p>
            </div>

            {{-- Existing Photos --}}
            @if($car->images->isNotEmpty())
                <div>
                    <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                        Foto Saat Ini ({{ $car->images->count() }} Foto)
                    </span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5">
                        @foreach($car->images as $img)
                            <div x-data="{ markedDelete: false }"
                                 :class="markedDelete ? 'opacity-40 ring-2 ring-rose-400' : ''"
                                 class="aspect-[4/3] rounded-2xl overflow-hidden relative border border-slate-200 bg-slate-100 group shadow-sm transition-all">
                                <img src="{{ $img->path }}" alt="Foto {{ $loop->iteration }}" class="w-full h-full object-cover">

                                {{-- Badge Foto Utama --}}
                                @if($img->is_primary)
                                    <span class="absolute top-2 left-2 bg-[#0f1e36] text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-md z-10">
                                        ★ UTAMA
                                    </span>
                                @endif

                                {{-- Tombol hapus foto lama --}}
                                <template x-if="!markedDelete">
                                    <button type="button"
                                            @click="markedDelete = true; markDeleteExisting({{ $img->id }})"
                                            title="Hapus Foto"
                                            class="absolute top-2 right-2 w-6 h-6 rounded-full bg-black/60 hover:bg-rose-600 text-white flex items-center justify-center text-[10px] font-bold backdrop-blur-sm shadow-sm opacity-0 group-hover:opacity-100 transition-all duration-200 hover:scale-110 z-10">
                                        ✕
                                    </button>
                                </template>

                                {{-- Undo hapus --}}
                                <template x-if="markedDelete">
                                    <button type="button"
                                            @click="markedDelete = false; deleteImageIds = deleteImageIds.filter(i => i !== {{ $img->id }})"
                                            class="absolute inset-0 flex items-center justify-center bg-black/40 text-white text-xs font-bold z-10">
                                        ↩ Batalkan
                                    </button>
                                </template>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Upload Foto Baru --}}
            <div>
                <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-slate-300 rounded-3xl cursor-pointer hover:border-[#0f1e36] hover:bg-slate-50/50 transition p-6 text-center group">
                    <div class="w-10 h-10 rounded-2xl bg-slate-100 group-hover:bg-blue-50 text-[#0f1e36] group-hover:text-blue-600 flex items-center justify-center mb-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-extrabold text-[#0f1e36]">
                        <span x-text="imagePreviews.length > 0 ? '+ Tambah Foto Lainnya' : '+ Tambah Foto Baru'"></span>
                    </span>
                    <span class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP — maks 5MB per foto</span>
                    <input type="file"
                           name="images[]"
                           x-ref="fileInput"
                           multiple
                           accept="image/*"
                           @change="handleFileSelect($event)"
                           class="hidden">
                </label>
            </div>

            {{-- Preview Foto Baru --}}
            <template x-if="imagePreviews.length > 0">
                <div>
                    <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                        Foto Baru Ditambahkan (<span x-text="imagePreviews.length"></span> Foto)
                    </span>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5">
                        <template x-for="(preview, index) in imagePreviews" :key="index">
                            <div class="aspect-[4/3] rounded-2xl overflow-hidden relative border border-blue-200 bg-slate-100 group shadow-sm">
                                <img :src="preview.url" :alt="preview.name" class="w-full h-full object-cover">
                                <span class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-md z-10">BARU</span>
                                <button type="button"
                                        @click="removeNewImage(index)"
                                        class="absolute top-2 right-2 w-6 h-6 rounded-full bg-black/60 hover:bg-rose-600 text-white flex items-center justify-center text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-all duration-200 hover:scale-110 z-10">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        {{-- =================== SUBMIT ACTION BAR =================== --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h4 class="font-bold text-[#0f1e36] text-sm">Sudah selesai mengedit?</h4>
                <p class="text-xs text-slate-500 mt-0.5">Perubahan akan langsung diterapkan dan terlihat oleh calon pembeli.</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('my-ads') }}"
                   class="px-6 py-3.5 rounded-2xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition text-center w-full sm:w-auto">
                    Batal
                </a>
                <button type="submit"
                        class="px-8 py-3.5 rounded-2xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white text-sm font-extrabold shadow hover:shadow-md transition active:scale-95 text-center w-full sm:w-auto shrink-0">
                    Simpan Perubahan →
                </button>
            </div>
        </div>

    </form>
</div>
@endsection
