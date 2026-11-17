@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-[#0f1e36] tracking-tight">Official Car Brands</h1>
        <p class="text-slate-600 mt-1">Jelajahi mobil bekas terpercaya berdasarkan merek resmi favorit Anda.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach($brands as $brand)
            <a href="{{ route('cars.index', ['brand' => $brand->slug]) }}" 
               class="bg-white rounded-2xl p-6 border border-slate-200/80 hover:border-slate-400/80 hover:shadow-md transition text-center group flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-xl font-bold text-slate-800 mb-3 group-hover:bg-[#0f1e36] group-hover:text-white transition">
                    {{ substr($brand->name, 0, 2) }}
                </div>
                <h3 class="font-bold text-[#0f1e36] text-base group-hover:text-blue-600 transition">{{ $brand->name }}</h3>
                <span class="text-xs text-slate-500 mt-1">{{ $brand->active_cars_count }} unit aktif</span>
            </a>
        @endforeach
    </div>
</div>
@endsection
