@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition flex items-center gap-1 mb-2">
            &larr; Kembali ke Beranda
        </a>
        <h1 class="text-3xl font-extrabold text-[#0f1e36] tracking-tight">Post Your Vehicle Ad</h1>
        <p class="text-slate-600 mt-1">Pasang iklan mobil Anda langsung tanpa biaya dealer. Iklan langsung tayang (Post-Moderation).</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm">
        <form action="{{ route('cars.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Judul Iklan</label>
                    <input type="text" name="title" required 
                           placeholder="Contoh: Toyota Fortuner 2.4 VRZ Diesel AT 2021 Tangan Pertama Istimewa"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#0f1e36] text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Merek Mobil</label>
                        <select name="brand_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#0f1e36] text-sm bg-white">
                            <option value="">Pilih Merek</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Harga (Rupiah)</label>
                        <input type="number" name="price" required placeholder="Contoh: 350000000"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#0f1e36] text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Deskripsi Kondisi & Surat-Surat</label>
                    <textarea name="description" rows="5" required 
                              placeholder="Jelaskan kondisi bodi, mesin, riwayat servis bengkel resmi, status BPKB on-hand, dan masa berlaku pajak..."
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#0f1e36] text-sm"></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white font-extrabold text-sm shadow transition">
                    Terbitkan Iklan Sekarang (Langsung Tayang)
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
