<x-guest-layout>
    <!-- Heading -->
    <div class="mb-7 text-center">
        <h1 class="text-2xl font-extrabold text-[#0f1e36] tracking-tight">Daftar Akun Carvia</h1>
        <p class="text-sm text-slate-500 mt-1">Gratis selamanya — akses kontak penjual dan pasang iklan mobil Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="email@kamu.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Nomor WhatsApp -->
        <div>
            <x-input-label for="phone" value="Nomor WhatsApp" />
            <div class="flex">
                <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-sm font-bold text-slate-500">
                    +62
                </span>
                <x-text-input id="phone" type="tel" name="phone" :value="old('phone')" required
                    placeholder="8123456789"
                    class="rounded-l-none border-l-0 flex-1" />
            </div>
            <p class="text-xs text-slate-400 mt-1 font-medium">Dipakai untuk tombol WA direct di iklan Anda.</p>
            <x-input-error :messages="$errors->get('phone')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Konfirmasi Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <!-- Submit -->
        <x-primary-button class="mt-2">
            Buat Akun Gratis
        </x-primary-button>
    </form>

    <!-- Divider -->
    <div class="my-6 flex items-center gap-3">
        <div class="flex-1 h-px bg-slate-100"></div>
        <span class="text-xs font-bold text-slate-400">SUDAH PUNYA AKUN?</span>
        <div class="flex-1 h-px bg-slate-100"></div>
    </div>

    <a href="{{ route('login') }}"
       class="block w-full py-3 rounded-xl border-2 border-slate-200 hover:border-[#0f1e36] text-center text-sm font-bold text-slate-700 hover:text-[#0f1e36] transition">
        Masuk ke Akun Saya
    </a>
</x-guest-layout>
