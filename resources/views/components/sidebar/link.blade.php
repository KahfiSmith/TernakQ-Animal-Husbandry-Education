@props(['href', 'active' => false])

<a href="{{ $href }}"
   {{ $attributes->class([
       'flex items-center gap-x-4 px-3 py-2 rounded-md transition duration-150 ease-in-out',
       'bg-pewterBlue text-white font-bold hover:bg-pewterBlue hover:text-white' => $active,
       'text-gray-200 hover:bg-pewterBlue hover:text-cosmicLatte font-semibold' => !$active,
   ]) }}>
    {{ $slot }}
</a>
