@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-gray-100 bg-polishedPine focus:outline-none focus:text-gray-100 focus:bg-polishedPine focus:border-pewterBlue transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-100 hover:text-white hover:bg-pewterBlue hover:border-white focus:outline-none focus:text-white focus:bg-gray-50 focus:border-white transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
