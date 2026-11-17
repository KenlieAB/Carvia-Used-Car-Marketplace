@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0f1e36] tracking-tight">
                Private-Party Inventory
            </h1>
            <p class="text-slate-600 mt-1 text-sm sm:text-base">
                Click any vehicle to inspect specifications and view the seller contact details.
            </p>
        </div>
        <div class="text-sm font-bold text-slate-500 whitespace-nowrap">
            <span class="text-[#0f1e36] font-extrabold text-base">{{ $totalCars }}</span> listings
        </div>
    </div>

    <!-- Filter Container Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm mb-10">
        <!-- Horizontal Brand Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 scrollbar-none border-b border-slate-100 mb-6">
            <a href="{{ route('cars.index', request()->except('brand', 'page')) }}" 
               class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition shrink-0 {{ !request('brand') ? 'bg-[#0f1e36] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                All Brands
            </a>
            @foreach($brands as $brand)
                <a href="{{ route('cars.index', array_merge(request()->except('page'), ['brand' => $brand->slug])) }}" 
                   class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition shrink-0 {{ request('brand') === $brand->slug ? 'bg-[#0f1e36] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $brand->name }}
                </a>
            @endforeach
        </div>

        <!-- Filter Controls Form -->
        <form action="{{ route('cars.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @if(request('brand'))
                <input type="hidden" name="brand" value="{{ request('brand') }}">
            @endif

            <!-- Body Style Filter -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Body Style</label>
                <select name="body_type" onchange="this.form.submit()" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                    <option value="">Body: All Styles</option>
                    <option value="suv" {{ request('body_type') == 'suv' ? 'selected' : '' }}>SUV</option>
                    <option value="mpv" {{ request('body_type') == 'mpv' ? 'selected' : '' }}>MPV</option>
                    <option value="sedan" {{ request('body_type') == 'sedan' ? 'selected' : '' }}>Sedan</option>
                    <option value="hatchback" {{ request('body_type') == 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                    <option value="city_car" {{ request('body_type') == 'city_car' ? 'selected' : '' }}>City Car</option>
                </select>
            </div>

            <!-- Transmission Filter -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Transmission</label>
                <select name="transmission" onchange="this.form.submit()" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                    <option value="">Transmission: Any</option>
                    <option value="automatic" {{ request('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                    <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                </select>
            </div>

            <!-- Max Price Filter -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Max Price</label>
                <select name="max_price" onchange="this.form.submit()" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                    <option value="">Max Price: Any</option>
                    <option value="150000000" {{ request('max_price') == '150000000' ? 'selected' : '' }}>< Rp 150 Juta</option>
                    <option value="250000000" {{ request('max_price') == '250000000' ? 'selected' : '' }}>< Rp 250 Juta</option>
                    <option value="350000000" {{ request('max_price') == '350000000' ? 'selected' : '' }}>< Rp 350 Juta</option>
                    <option value="500000000" {{ request('max_price') == '500000000' ? 'selected' : '' }}>< Rp 500 Juta</option>
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sort By</label>
                <select name="sort" onchange="this.form.submit()" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0f1e36]">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort: Newest Listed</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="mileage_low" {{ request('sort') == 'mileage_low' ? 'selected' : '' }}>Mileage: Lowest</option>
                    <option value="year_high" {{ request('sort') == 'year_high' ? 'selected' : '' }}>Year: Newest</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Car Cards Grid (2 Kolom sesuai referensi Screenshot 4) -->
    @if($cars->isEmpty())
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80">
            <p class="text-slate-600 font-bold text-base">Tidak ada mobil yang sesuai dengan kriteria filter Anda.</p>
            <a href="{{ route('cars.index') }}" class="inline-block mt-3 px-5 py-2.5 rounded-xl bg-[#0f1e36] text-white text-xs font-bold shadow-sm">
                Reset Semua Filter
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            @foreach($cars as $car)
                <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md hover:border-slate-300 transition duration-200 group flex flex-col justify-between">
                    
                    <!-- Top Section: Image & Header -->
                    <div>
                        <!-- Car Photo -->
                        <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                            <img src="{{ $car->primaryImage ? $car->primaryImage->path : 'https://placehold.co/800x500?text=Mobil+Bekas' }}" 
                                 alt="{{ $car->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out">
                            
                            @if($car->status === 'sold')
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                    <span class="px-4 py-1.5 rounded-xl bg-red-600 text-white font-extrabold text-sm uppercase tracking-wider">
                                        SOLD OUT
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 pb-4">
                            <!-- Make & Year -->
                            <div class="text-xs font-extrabold uppercase tracking-widest text-slate-500 mb-1">
                                {{ $car->carModel->brand->name }} • {{ $car->manufacture_year }}
                            </div>

                            <!-- Model Title -->
                            <h3 class="text-xl font-extrabold text-[#0f1e36] tracking-tight group-hover:text-blue-600 transition line-clamp-1 mb-2">
                                <a href="{{ route('cars.show', $car->slug) }}">
                                    {{ $car->title }}
                                </a>
                            </h3>

                            <!-- Price -->
                            <div class="text-2xl font-black text-[#0f1e36] tracking-tight mb-3">
                                Rp {{ number_format($car->price, 0, ',', '.') }}
                                @if($car->price_negotiable)
                                    <span class="text-xs font-bold text-slate-500 tracking-normal ml-1">(Nego)</span>
                                @endif
                            </div>

                            <!-- Key Specs Line -->
                            <div class="text-xs font-semibold text-slate-600 flex flex-wrap items-center gap-1.5">
                                <span>{{ number_format($car->mileage_km, 0, ',', '.') }} km</span>
                                <span>•</span>
                                <span class="capitalize">{{ $car->transmission }}</span>
                                <span>•</span>
                                <span>{{ $car->city->name }}</span>
                                <span>•</span>
                                <span class="text-slate-800">Plat {{ $car->plate_region }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Bar: Seller Info & Action -->
                    <div class="px-6 py-4 bg-slate-50/70 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-600 font-medium">
                            Seller: <strong class="text-slate-900">{{ $car->user->name }}</strong>
                        </span>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('cars.show', $car->slug) }}" 
                               class="font-bold text-[#0f1e36] hover:text-blue-600 transition flex items-center gap-1">
                                View Contact &rarr;
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $cars->links() }}
        </div>
    @endif

</div>
@endsection
