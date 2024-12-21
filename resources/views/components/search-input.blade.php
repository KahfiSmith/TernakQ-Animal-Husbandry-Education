@props(['disabled' => false])

<div class="relative group">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-700 
                 pointer-events-none transform transition-transform duration-150 ease-in-out
                 group-focus-within:translate-x-0.5 group-focus-within:translate-y-0.5 z-10">
        <i class="fas fa-search"></i>
    </span>

    <input 
        type="search" 
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge([
            'class' =>
                'pl-10 ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
                 focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 
                 focus:translate-x-0.5 rounded-md focus:outline-none 
                 focus:border-gray-700 focus:ring-2 focus:ring-gray-700 
                 text-gray-700 leading-5 transition-transform duration-150 ease-in-out px-4 py-3 tracking-wide relative z-0'
        ]) !!}
    >
</div>
