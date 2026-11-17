@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
