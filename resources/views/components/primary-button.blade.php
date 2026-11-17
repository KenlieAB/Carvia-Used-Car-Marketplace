<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full py-3.5 px-6 rounded-xl bg-[#0f1e36] hover:bg-[#1a2f50] text-white font-extrabold text-sm shadow-sm transition active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#0f1e36] focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
