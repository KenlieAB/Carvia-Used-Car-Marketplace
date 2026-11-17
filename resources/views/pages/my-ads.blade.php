@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0f1e36] tracking-tight">Garasi Saya (My Ads)</h1>
            <p class="text-slate-600 mt-1">Kelola listing mobil yang sedang Anda iklankan atau tandai unit yang sudah terjual.</p>
        </div>
        <a href="{{ route('cars.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white text-sm font-bold shadow-sm transition">
            + Pasang Iklan Baru
        </a>
    </div>

    <!-- Listing Table / Cards -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if($userCars->isEmpty())
            <div class="p-12 text-center">
                <p class="text-slate-500 font-medium">Anda belum memasang iklan mobil apapun.</p>
                <a href="{{ route('cars.create') }}" class="inline-block mt-4 text-blue-600 font-bold hover:underline">
                    Mulai Pasang Iklan Sekarang &rarr;
                </a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($userCars as $car)
                    <div class="p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:bg-slate-50/50 transition">
                        <div class="flex items-center gap-4">
                            <img src="{{ $car->primaryImage ? $car->primaryImage->path : 'https://placehold.co/120x80?text=No+Photo' }}" 
                                 alt="{{ $car->title }}" 
                                 class="w-24 h-20 rounded-xl object-cover border border-slate-200 shrink-0">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $car->carModel->brand->name }} • {{ $car->manufacture_year }}</span>
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $car->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }} font-bold uppercase">
                                        {{ $car->status }}
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-[#0f1e36] hover:text-blue-600 transition">
                                    <a href="{{ route('cars.show', $car->slug) }}">{{ $car->title }}</a>
                                </h3>
                                <p class="text-sm font-extrabold text-[#0f1e36] mt-0.5">
                                    Rp {{ number_format($car->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                            <a href="{{ route('cars.show', $car->slug) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                Lihat Unit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
