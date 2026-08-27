@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-bold text-base text-slate-700 dark:text-slate-300']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-red-500 dark:text-red-400 ml-0.5" aria-hidden="true">*</span>
    @endif
</label>
