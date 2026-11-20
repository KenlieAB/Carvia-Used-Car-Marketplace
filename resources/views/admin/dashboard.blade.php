@extends('layouts.admin')

@php
    $pageTitle = 'Ringkasan Platform';
@endphp

@section('admin-content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-extrabold text-[#0f1e36] tracking-tight">Ringkasan Sistem</h1>
        <p class="text-xs text-slate-500 mt-1">Pantau status listing, aduan pengguna, dan pertumbuhan marketplace Carvia.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Active Cars -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Iklan Aktif</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">🚗</span>
            </div>
            <div class="text-2xl font-black text-[#0f1e36] mt-3">{{ number_format($stats['active_cars']) }}</div>
            <p class="text-[11px] text-slate-500 mt-1">Dari total {{ number_format($stats['total_cars']) }} iklan dibuat</p>
        </div>

        <!-- Pending Reports -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aduan Pending</span>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs">⚠️</span>
            </div>
            <div class="text-2xl font-black {{ $stats['pending_reports'] > 0 ? 'text-rose-600' : 'text-[#0f1e36]' }} mt-3">
                {{ number_format($stats['pending_reports']) }}
            </div>
            <p class="text-[11px] text-slate-500 mt-1">Perlu ditindaklanjuti</p>
        </div>

        <!-- Sold Cars -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Unit Terjual</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs">🎉</span>
            </div>
            <div class="text-2xl font-black text-[#0f1e36] mt-3">{{ number_format($stats['sold_cars']) }}</div>
            <p class="text-[11px] text-slate-500 mt-1">Iklan ditandai laku</p>
        </div>

        <!-- Users -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">User Terdaftar</span>
                <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs">👥</span>
            </div>
            <div class="text-2xl font-black text-[#0f1e36] mt-3">{{ number_format($stats['total_users']) }}</div>
            <p class="text-[11px] text-slate-500 mt-1">Penjual & Pembeli terdaftar</p>
        </div>
    </div>

    <!-- 2 Column Section: Recent Cars & Recent Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Cars -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-[#0f1e36]">Listing Mobil Terbaru</h3>
                <a href="{{ route('admin.cars.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua &rarr;</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentCars as $car)
                    <div class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50/50 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="{{ $car->primaryImage ? $car->primaryImage->path : 'https://placehold.co/80x60?text=No+Photo' }}" 
                                 class="w-12 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                            <div class="min-w-0">
                                <p class="text-xs font-extrabold text-[#0f1e36] truncate">{{ $car->title }}</p>
                                <p class="text-[11px] text-slate-400 truncate">Oleh: {{ $car->user->name ?? 'User' }} · Rp {{ number_format($car->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 {{ $car->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($car->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-600') }}">
                            {{ strtoupper($car->status) }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400">Belum ada mobil terdaftar.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-extrabold text-[#0f1e36]">Aduan Pending Terbaru</h3>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua &rarr;</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentReports as $rep)
                    <div class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50/50 transition">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200">
                                    {{ $rep->reason }}
                                </span>
                                <span class="text-[10px] text-slate-400">{{ $rep->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs font-bold text-[#0f1e36] truncate">Iklan: {{ $rep->car->title ?? 'Unit Dihapus' }}</p>
                            <p class="text-[11px] text-slate-500 truncate italic">"{{ $rep->notes ?? 'Tanpa catatan' }}"</p>
                        </div>
                        <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-slate-600 hover:text-blue-600 shrink-0">
                            Proses &rarr;
                        </a>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-emerald-600 font-semibold">
                        ✓ Tidak ada aduan pending saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
