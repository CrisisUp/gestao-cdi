@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-slate-800 dark:border-emerald-500 text-base font-black leading-5 text-slate-900 dark:text-emerald-400 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-base font-bold leading-5 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600 focus:outline-none transition duration-150 ease-in-out';
@endphp


<a {{ ($active ?? false) ? 'aria-current=page' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
