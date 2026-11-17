<x-guest-layout>
    <!-- Pesan kontekstual jika diarahkan dari detail mobil -->
    @if(session('status') || session()->has('url.intended'))
        <div class="mb-5 p-4 rounded-2xl bg-blue-50 border border-blue-200/80 text-center">
            <p class="text-sm font-bold text-blue-800">
                🔒 Login diperlukan untuk melihat detail mobil & kontak penjual.
            </p>
            <p class="text-xs text-blue-600 mt-0.5">Daftar gratis atau masuk untuk melanjutkan.</p>
        </div>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Heading -->
    <div class="mb-7 text-center">
        <h1 class="text-2xl font-extrabold text-[#0f1e36] tracking-tight">Masuk ke Carvia</h1>
        <p class="text-sm text-slate-500 mt-1">Akses detail mobil dan kontak penjual secara langsung.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="email@kamu.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-slate-300 text-[#0f1e36] focus:ring-[#0f1e36]">
                <span class="text-xs font-semibold text-slate-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs font-bold text-slate-500 hover:text-[#0f1e36] transition underline underline-offset-2">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <x-primary-button class="mt-2">
            Masuk Sekarang
        </x-primary-button>
    </form>

    <!-- Divider -->
    <div class="my-6 flex items-center gap-3">
        <div class="flex-1 h-px bg-slate-100"></div>
        <span class="text-xs font-bold text-slate-400">BELUM PUNYA AKUN?</span>
        <div class="flex-1 h-px bg-slate-100"></div>
    </div>

    <!-- Register Link -->
    <a href="{{ route('register') }}"
       class="block w-full py-3 rounded-xl border-2 border-slate-200 hover:border-[#0f1e36] text-center text-sm font-bold text-slate-700 hover:text-[#0f1e36] transition">
        Daftar Gratis — Gratis Selamanya
    </a>
</x-guest-layout>
