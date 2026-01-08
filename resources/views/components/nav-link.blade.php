@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-slate-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md'
            : 'text-slate-300 hover:bg-slate-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="mr-3 flex-shrink-0 h-6 w-6 text-slate-400 group-hover:text-slate-300">
        {{ $icon }}
    </div>
    {{ $slot }}
</a>
