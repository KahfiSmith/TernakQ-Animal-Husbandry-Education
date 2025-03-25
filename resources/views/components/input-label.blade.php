@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 mb-2']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-red-500 -ml-0.5">*</span>
    @endif
</label>
