<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="/images/logo.svg" type="image/png">
        <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col ml-64">
                <!-- Navigation -->
                <livewire:layout.navigation />

                <!-- Page Content -->
                <main class="lg:p-6 bg-red-500">
                    {{ $slot }}
                </main>
                
            </div>
        </div>
    </body>
</html>
