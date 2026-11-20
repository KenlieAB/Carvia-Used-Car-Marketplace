@extends('layouts.admin')

@php
    $pageTitle = 'Moderasi Iklan Mobil';
@endphp

@section('admin-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0f1e36] tracking-tight">Moderasi Iklan Mobil</h1>
            <p class="text-xs text-slate-500 mt-1">Tinjau semua iklan mobil yang terpasang. Anda dapat menolak/menangguhkan atau menghapus permanen.</p>
        </div>
    </div>

    <!-- Filter Pills & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <!-- Status Tabs -->
        <div class="flex items-center gap-1 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
            @php $currentStatus = request('status', ''); @endphp
            <a href="{{ route('admin.cars.index') }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $currentStatus === '' ? 'bg-[#0f1e36] text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Semua ({{ $statusCounts['all'] }})
            </a>
            <a href="{{ route('admin.cars.index', ['status' => 'active']) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $currentStatus === 'active' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Aktif ({{ $statusCounts['active'] }})
            </a>
            <a href="{{ route('admin.cars.index', ['status' => 'rejected']) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $currentStatus === 'rejected' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Ditangguhkan / Rejected ({{ $statusCounts['rejected'] }})
            </a>
            <a href="{{ route('admin.cars.index', ['status' => 'sold']) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $currentStatus === 'sold' ? 'bg-slate-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Terjual ({{ $statusCounts['sold'] }})
            </a>
        </div>

        <!-- Search Form -->
        <form action="{{ route('admin.cars.index') }}" method="GET" class="w-full md:w-72">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, plat, penjual..." 
                       class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-slate-200 focus:ring-2 focus:ring-[#0f1e36] focus:outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </form>
    </div>

    <!-- Cars Table -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-extrabold uppercase tracking-wider">
                    <tr>
                        <th class="p-4">Unit Mobil</th>
                        <th class="p-4">Penjual & Kontak</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal Tayang</th>
                        <th class="p-4 text-right">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($cars as $car)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $car->primaryImage ? $car->primaryImage->path : 'https://placehold.co/80x60?text=No+Photo' }}" 
                                         class="w-16 h-12 rounded-xl object-cover border border-slate-200 shrink-0">
                                    <div>
                                        <a href="{{ route('cars.show', $car->slug) }}" target="_blank" class="font-extrabold text-[#0f1e36] hover:text-blue-600 transition block">
                                            {{ $car->title }} ↗
                                        </a>
                                        <p class="text-slate-400 text-[11px] mt-0.5">{{ $car->city->name ?? '-' }} · Plat: {{ $car->plate_region ?? '-' }} · {{ $car->manufacture_year }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-slate-700">{{ $car->user->name ?? 'User Tak Dikenal' }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $car->user->phone ?? $car->user->email ?? '-' }}</p>
                            </td>
                            <td class="p-4 font-black text-[#0f1e36]">
                                Rp {{ number_format($car->price, 0, ',', '.') }}
                            </td>
                            <td class="p-4">
                                @php
                                    $badge = match($car->status) {
                                        'active' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        'sold' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        default => 'bg-amber-100 text-amber-700 border-amber-200'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-full border text-[10px] font-extrabold uppercase {{ $badge }}">
                                    {{ $car->status }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-500 text-[11px]">
                                {{ $car->created_at->format('d M Y') }}
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($car->status === 'active')
                                        <!-- Reject / Suspend -->
                                        <form action="{{ route('admin.cars.reject', $car) }}" method="POST"
                                              onsubmit="return confirm('Tangguhkan/Takedown iklan ini? Iklan tidak akan muncul di publik.')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 text-[11px] font-bold transition">
                                                Takedown
                                            </button>
                                        </form>
                                    @elseif($car->status === 'rejected')
                                        <!-- Restore -->
                                        <form action="{{ route('admin.cars.restore', $car) }}" method="POST"
                                              onsubmit="return confirm('Aktifkan kembali iklan ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-[11px] font-bold transition">
                                                Pulihkan
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Force Delete -->
                                    <form action="{{ route('admin.cars.destroy', $car) }}" method="POST"
                                          onsubmit="return confirm('Hapus permanen iklan ini beserta seluruh file fotonya? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Permanen"
                                                class="p-1.5 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Tidak ada mobil yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cars->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $cars->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
