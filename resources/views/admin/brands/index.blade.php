@extends('layouts.admin')

@php
    $pageTitle = 'Katalog Merek & Model';
@endphp

@section('admin-content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-extrabold text-[#0f1e36] tracking-tight">Katalog Merek & Model Mobil</h1>
        <p class="text-xs text-slate-500 mt-1">Kelola daftar merek resmi dan varian model untuk form pasang iklan dan filter pencarian.</p>
    </div>

    <!-- Forms Grid: Add Brand & Add Model -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Form Tambah Merek -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
            <h3 class="text-sm font-extrabold text-[#0f1e36] mb-3">Tambah Merek Baru</h3>
            <form action="{{ route('admin.brands.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Merek *</label>
                    <input type="text" name="name" required placeholder="Contoh: BYD, Chery, Hyundai" 
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0f1e36] focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Negara Asal</label>
                    <input type="text" name="country" placeholder="Contoh: Jepang, Korea Selatan, China" 
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0f1e36] focus:outline-none">
                </div>
                <button type="submit" class="px-4 py-2 bg-[#0f1e36] hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                    + Simpan Merek
                </button>
            </form>
        </div>

        <!-- Form Tambah Model -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
            <h3 class="text-sm font-extrabold text-[#0f1e36] mb-3">Tambah Model ke Merek</h3>
            <form action="{{ route('admin.models.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Pilih Merek *</label>
                    <select name="brand_id" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0f1e36] focus:outline-none bg-white">
                        <option value="">-- Pilih Merek --</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Model *</label>
                    <input type="text" name="name" required placeholder="Contoh: Seal, Omoda 5, Ioniq 5" 
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0f1e36] focus:outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Tipe Bodi (Body Type) *</label>
                    <select name="body_type" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#0f1e36] focus:outline-none bg-white">
                        <option value="suv">SUV</option>
                        <option value="mpv">MPV</option>
                        <option value="sedan">Sedan</option>
                        <option value="hatchback">Hatchback</option>
                        <option value="city_car">City Car</option>
                        <option value="coupe">Coupe / Sport</option>
                        <option value="commercial">Commercial / Pick-up</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition">
                    + Simpan Model
                </button>
            </form>
        </div>
    </div>

    <!-- Brands & Models Directory Accordion/List -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-[#0f1e36]">Daftar Merek & Model Terdaftar</h3>
            <span class="text-xs text-slate-400 font-bold">Total: {{ $brands->total() }} Merek</span>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach($brands as $brand)
                <div class="p-5 hover:bg-slate-50/50 transition" x-data="{ expanded: false }">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-slate-100 font-black text-slate-700 text-xs flex items-center justify-center">
                                {{ strtoupper(substr($brand->name, 0, 2)) }}
                            </span>
                            <div>
                                <h4 class="text-sm font-black text-[#0f1e36]">{{ $brand->name }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $brand->country ?? 'Negara tidak diset' }} · {{ $brand->car_models_count }} Model</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button @click="expanded = !expanded" type="button" 
                                    class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition">
                                <span x-text="expanded ? 'Tutup Model' : 'Lihat Model (' + {{ $brand->car_models_count }} + ')'"></span>
                            </button>

                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
                                  onsubmit="return confirm('Hapus merek {{ $brand->name }} beserta seluruh modelnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Merek">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Models Sublist -->
                    <div x-show="expanded" class="mt-4 pt-4 border-t border-slate-100" style="display: none;">
                        @if($brand->carModels->isEmpty())
                            <p class="text-xs text-slate-400 italic">Belum ada model untuk merek ini.</p>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                @foreach($brand->carModels as $model)
                                    <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
                                        <div>
                                            <p class="font-extrabold text-slate-800">{{ $model->name }}</p>
                                            <p class="text-[10px] text-slate-400 uppercase font-semibold">{{ $model->body_type }}</p>
                                        </div>
                                        <form action="{{ route('admin.models.destroy', $model) }}" method="POST"
                                              onsubmit="return confirm('Hapus model {{ $model->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 p-1 rounded transition">
                                                ✕
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($brands->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $brands->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
