<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RocketDataHub') }} - @yield('title', 'Smart Data Bundles')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.documentElement.classList.remove('dark');
    </script>
</head>

<body
    class="antialiased min-h-screen text-foreground selection:bg-primary/20 selection:text-primary transition-colors duration-300">

    {{-- Site-wide gradient background --}}
    @include('partials._site_bg')

    <!-- Sonner Toasts -->
    <x-toaster />
    <x-announcement-modal />
    {{-- <x-notifications /> --}}



    <div class="flex flex-col min-h-screen">
        @unless($hideNav ?? false)
            @include('partials.navbar')
        @endunless

        <main class="flex-1 flex flex-col animate-fade-in-up">
            @yield('content')
        </main>

        @unless($hideNav ?? false)
            @include('partials.footer')
        @endunless
    </div>

    @stack('scripts')
</body>

</html>