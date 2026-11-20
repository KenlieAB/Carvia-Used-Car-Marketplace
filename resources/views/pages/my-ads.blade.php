@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0f1e36] tracking-tight">Garasi Saya</h1>
            <p class="text-slate-500 mt-1 text-sm">Kelola semua iklan mobil yang Anda pasang di Carvia.</p>
        </div>
        <a href="{{ route('cars.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white text-sm font-bold shadow-sm transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Pasang Iklan Baru
        </a>
    </div>

    {{-- Flash Success --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 text-center">
            <div class="text-3xl font-black text-[#0f1e36]">{{ $stats['total'] }}</div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Total Iklan</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 text-center">
            <div class="text-3xl font-black text-emerald-600">{{ $stats['active'] }}</div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Tayang Aktif</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 text-center">
            <div class="text-3xl font-black text-slate-400">{{ $stats['sold'] }}</div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Terjual</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 text-center">
            <div class="text-3xl font-black text-rose-500">{{ $stats['expired'] }}</div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Kadaluarsa</div>
        </div>
    </div>

    {{-- Listing Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

        @if($userCars->isEmpty())
            <div class="p-16 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <p class="text-slate-700 font-bold text-lg">Belum ada iklan</p>
                <p class="text-slate-500 text-sm mt-1">Mulai jual mobil Anda sekarang, gratis dan tanpa perantara.</p>
                <a href="{{ route('cars.create') }}"
                   class="inline-block mt-5 px-6 py-3 rounded-xl bg-[#0f1e36] text-white text-sm font-bold hover:bg-[#1a2f50] transition">
                    Pasang Iklan Pertama Anda →
                </a>
            </div>

        @else
            <div class="divide-y divide-slate-100">
                @foreach($userCars as $car)
                    @php
                        $daysLeft    = $car->expired_at ? (int) now()->diffInDays($car->expired_at, false) : null;
                        $totalDays   = 30; // masa tayang default
                        $pct         = $daysLeft !== null ? max(0, min(100, round(($daysLeft / $totalDays) * 100))) : 0;
                        $isExpired   = $daysLeft !== null && $daysLeft < 0;
                        $isWarning   = $daysLeft !== null && $daysLeft >= 0 && $daysLeft <= 7;

                        $barColor = match(true) {
                            $isExpired  => 'bg-rose-500',
                            $isWarning  => 'bg-amber-400',
                            $pct > 50   => 'bg-emerald-500',
                            default     => 'bg-blue-500',
                        };
                        $daysLabel = match(true) {
                            $isExpired  => 'Kadaluarsa',
                            $daysLeft === 0 => 'Berakhir hari ini',
                            $daysLeft !== null => $daysLeft . ' hari lagi',
                            default     => '—',
                        };
                        $daysLabelColor = match(true) {
                            $isExpired => 'text-rose-600',
                            $isWarning => 'text-amber-600',
                            default    => 'text-slate-600',
                        };

                        $statusLabel = match($car->status) {
                            'active'  => 'Aktif',
                            'sold'    => 'Terjual',
                            'expired' => 'Kadaluarsa',
                            default   => ucfirst($car->status),
                        };
                        $statusClass = match($car->status) {
                            'active'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'sold'    => 'bg-slate-100 text-slate-500 border-slate-200',
                            'expired' => 'bg-rose-100 text-rose-600 border-rose-200',
                            default   => 'bg-slate-100 text-slate-500 border-slate-200',
                        };
                    @endphp

                    <div class="p-5 sm:p-6 hover:bg-slate-50/40 transition-colors">
                        <div class="flex flex-col sm:flex-row items-start gap-4">

                            {{-- Foto --}}
                            <a href="{{ route('cars.show', $car->slug) }}" class="shrink-0">
                                <img src="{{ $car->primaryImage ? $car->primaryImage->path : 'https://placehold.co/120x90?text=No+Photo' }}"
                                     alt="{{ $car->title }}"
                                     class="w-28 h-20 sm:w-32 sm:h-24 rounded-xl object-cover border border-slate-200">
                            </a>

                            {{-- Info + Actions --}}
                            <div class="flex-1 min-w-0 space-y-2.5">

                                {{-- Row 1: Status badge + views --}}
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-[11px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                    <span class="text-[11px] text-slate-400 font-medium flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        {{ number_format($car->view_count) }} dilihat
                                    </span>
                                </div>

                                {{-- Row 2: Title + Price --}}
                                <div>
                                    <h3 class="text-sm font-extrabold text-[#0f1e36] leading-snug">
                                        <a href="{{ route('cars.show', $car->slug) }}" class="hover:text-blue-600 transition">
                                            {{ $car->title }}
                                        </a>
                                    </h3>
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-0.5">
                                        <span class="text-sm font-extrabold text-[#0f1e36]">
                                            Rp {{ number_format($car->price, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs text-slate-300">•</span>
                                        <span class="text-xs text-slate-500">{{ $car->city->name }}</span>
                                        <span class="text-xs text-slate-300">•</span>
                                        <span class="text-xs text-slate-500">{{ $car->manufacture_year }}</span>
                                    </div>
                                </div>

                                {{-- Row 3: Expiry Bar (hanya untuk iklan aktif) --}}
                                @if($car->status === 'active' && $daysLeft !== null)
                                    <div class="pt-0.5">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Masa Tayang</span>
                                            <span class="text-[11px] font-black {{ $daysLabelColor }} {{ $isWarning || $isExpired ? 'animate-pulse' : '' }}">
                                                @if($isExpired)
                                                    ⚠ Kadaluarsa
                                                @elseif($isWarning)
                                                    ⚠ {{ $daysLabel }}
                                                @else
                                                    {{ $daysLabel }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="{{ $barColor }} h-2 rounded-full transition-all"
                                                 style="width: {{ $pct }}%"></div>
                                        </div>
                                        @if($car->expired_at)
                                            <p class="text-[10px] text-slate-400 mt-1">
                                                Berakhir {{ $car->expired_at->translatedFormat('d F Y') }}
                                            </p>
                                        @endif
                                    </div>
                                @elseif($car->status === 'sold')
                                    <p class="text-[11px] text-slate-400">
                                        Ditandai terjual · Tidak tampil di halaman utama
                                    </p>
                                @endif

                                {{-- Row 4: Action Buttons (icon-only, mono grey) --}}
                                <div class="flex items-center gap-1 pt-0.5">

                                    {{-- Lihat --}}
                                    <a href="{{ route('cars.show', $car->slug) }}"
                                       title="Lihat Iklan"
                                       class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    @if($car->status !== 'sold')
                                        {{-- Edit --}}
                                        <a href="{{ route('cars.edit', $car->slug) }}"
                                           title="Edit Iklan"
                                           class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        {{-- Perpanjang --}}
                                        <form action="{{ route('cars.extend', $car->slug) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    title="Perpanjang 30 Hari"
                                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 {{ $isWarning || $isExpired ? 'ring-2 ring-amber-300 text-amber-500' : '' }} transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>

                                        {{-- Tandai Terjual --}}
                                        <form action="{{ route('cars.sold', $car->slug) }}" method="POST" class="inline"
                                              x-data
                                              @submit.prevent="if(confirm('Tandai iklan ini sebagai Terjual? Iklan tidak akan tampil di halaman utama.')) $el.submit()">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    title="Tandai Terjual"
                                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        {{-- Aktifkan Kembali --}}
                                        <form action="{{ route('cars.extend', $car->slug) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    title="Aktifkan Kembali"
                                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Hapus --}}
                                    <form action="{{ route('cars.destroy', $car->slug) }}" method="POST" class="inline"
                                          x-data
                                          @submit.prevent="if(confirm('Hapus iklan \"{{ addslashes($car->title) }}\"? Semua foto juga akan dihapus permanen.')) $el.submit()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Hapus Iklan"
                                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            {{-- Pagination --}}
            @if($userCars->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $userCars->links() }}
                </div>
            @endif

        @endif
    </div>
</div>
@endsection
