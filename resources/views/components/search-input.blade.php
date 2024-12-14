@props(['disabled' => false])

<input 
    type="search" 
    placeholder="Search..." 
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' =>
            'ring-2 ring-gray-700 shadow-[4px_4px_0px_2px_#374151] 
             focus:shadow-[2px_2px_0px_2px_#374151] focus:translate-y-0.5 
             focus:translate-x-0.5 rounded-md focus:outline-none 
             focus:border-none focus:ring-2 focus:ring-gray-700 
             text-gray-700 leading-5 transition duration-150 ease-in-out px-4 py-3'
    ]) !!}
>
