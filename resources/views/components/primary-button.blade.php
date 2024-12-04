<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex bg-orange justify-center items-center text-center px-6 py-2 rounded-md font-medium text-base tracking-widest focus:outline-none focus-visible:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
