@props(['href', 'active' => false])

<a href="{{ $href }}"
   {{ $attributes->class([
       'flex items-center gap-x-4 px-3 py-2 rounded-md text-gray-300 transition duration-50 ease-in-out',
       'bg-cosmicLatte text-white font-bold' => $active,
       'hover:bg-cosmicLatte hover:text-polishedPine' => !$active,
   ]) }}>
    {{ $slot }}
</a>
