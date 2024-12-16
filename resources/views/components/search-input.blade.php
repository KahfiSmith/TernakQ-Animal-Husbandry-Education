@props(['disabled' => false])

<div class="relative">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-700">
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
                 focus:border-none focus:ring-2 focus:ring-gray-700 
                 text-gray-700 leading-5 transition duration-150 ease-in-out px-4 py-3'
        ]) !!}
    >
</div>
