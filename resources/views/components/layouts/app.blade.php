<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/images/logo.svg" type="image/png">
    <title>Buat Topik</title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <main class="lg:p-6 mt-16">
                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts
</body>

</html>