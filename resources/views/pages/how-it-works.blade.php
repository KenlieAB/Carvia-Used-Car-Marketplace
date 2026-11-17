@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <span class="text-xs font-bold tracking-wider uppercase text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Panduan Marketplace P2P</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f1e36] tracking-tight mt-3">How Carvia Works</h1>
        <p class="text-slate-600 mt-2 text-base max-w-xl mx-auto">
            Platform jual beli mobil bekas langsung antar pemilik unit tanpa biaya perantara ataupun markup dealer.
        </p>
    </div>

    <!-- 3 Langkah Utama -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start gap-6">
            <span class="w-12 h-12 rounded-2xl bg-[#0f1e36] text-white flex items-center justify-center font-extrabold text-lg shrink-0">01</span>
            <div>
                <h3 class="text-xl font-bold text-[#0f1e36]">Pasang Iklan Mobil Anda Secara Lengkap</h3>
                <p class="text-slate-600 mt-2 text-sm leading-relaxed">
                    Pilih merek, model, tahun NIK & pemakaian, serta kilometer mobil. Cantumkan kejelasan surat (BPKB on-hand atau leasing), masa berlaku pajak, dan foto langsung dari smartphone Anda. Iklan langsung terbit tanpa menunggu lama (Post-Moderation).
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start gap-6">
            <span class="w-12 h-12 rounded-2xl bg-[#0f1e36] text-white flex items-center justify-center font-extrabold text-lg shrink-0">02</span>
            <div>
                <h3 class="text-xl font-bold text-[#0f1e36]">Pembeli Menghubungi Langsung via WhatsApp</h3>
                <p class="text-slate-600 mt-2 text-sm leading-relaxed">
                    Setiap iklan menampilkan tombol direct WhatsApp ke nomor pemilik mobil. Pembeli dapat langsung menanyakan ketersediaan unit, nego harga secara transparan, dan menentukan jadwal bertemu.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start gap-6">
            <span class="w-12 h-12 rounded-2xl bg-[#0f1e36] text-white flex items-center justify-center font-extrabold text-lg shrink-0">03</span>
            <div>
                <h3 class="text-xl font-bold text-[#0f1e36]">Janjian Cek Fisik Unit & Transaksi Aman</h3>
                <p class="text-slate-600 mt-2 text-sm leading-relaxed">
                    Bertemu di tempat aman (rumah penjual atau bengkel resmi), lakukan test drive, cek keaslian BPKB dan faktur, lalu selesaikan transaksi tanpa komisi dealer sepeser pun.
                </p>
            </div>
        </div>
    </div>

    <!-- Tips Keamanan -->
    <div class="mt-12 bg-amber-50 border border-amber-200 rounded-2xl p-6 sm:p-8">
        <h3 class="text-lg font-bold text-amber-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            Tips Transaksi Aman (Wajib Dibaca)
        </h3>
        <ul class="mt-3 text-sm text-amber-800 space-y-2 list-disc list-inside">
            <li><strong>Jangan pernah mentransfer DP/Tanda Jadi</strong> sebelum memeriksa fisik mobil dan keaslian surat-surat secara langsung.</li>
            <li>Pastikan nomor rangka dan nomor mesin sesuai dengan yang tertera di BPKB & STNK.</li>
            <li>Waspada jika harga unit jauh di bawah harga pasar wajar atau penjual beralasan tidak bisa bertemu langsung.</li>
        </ul>
    </div>
</div>
@endsection
