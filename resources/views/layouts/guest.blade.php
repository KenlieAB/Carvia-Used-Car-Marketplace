<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Auth' }} - Carvia</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#eef2f6] font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <!-- Auth Card Container -->
    <div class="w-full max-w-md">

        <!-- Brand Logo / Back to Home -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 group">
                <span class="text-3xl font-extrabold tracking-tight text-[#0f1e36] group-hover:opacity-80 transition">
                    Carvia
                </span>
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-600"></span>
            </a>
            <p class="text-xs text-slate-500 mt-1 font-medium">P2P Used Car Marketplace</p>
        </div>

        <!-- Auth Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-8 sm:p-10">
            {{ $slot }}
        </div>

        <!-- Footer link -->
        <p class="text-center text-xs text-slate-400 mt-6">
            &copy; {{ date('Y') }} Carvia Indonesia &mdash;
            <a href="{{ route('how-it-works') }}" class="hover:text-slate-600 transition underline">Tips Transaksi Aman</a>
        </p>
    </div>

</body>
</html>
