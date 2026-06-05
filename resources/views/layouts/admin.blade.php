<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RocketDataHub Admin') }} - @yield('title', 'Admin')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    <script>
        document.documentElement.classList.remove('dark');
    </script>
</head>

<body
    class="antialiased text-slate-900 dark:text-slate-100 transition-colors duration-300 min-h-screen"
    style="font-family: 'Poppins', 'Geist', sans-serif;"
    x-data="{ 
          sidebarOpen: false,
          isCollapsed: localStorage.getItem('admin_compact_sidebar') === 'true'
      }" x-init="
          $watch('isCollapsed', val => localStorage.setItem('admin_compact_sidebar', val));
      ">

    {{-- Site-wide gradient background --}}
    @include('partials._site_bg')

    <div class="min-h-screen">

        <x-admin-sidebar />

        <div class="flex flex-col min-h-screen transition-all duration-300"
            :class="isCollapsed ? 'lg:pl-20' : 'lg:pl-64'">

            <x-admin-navbar />

            <main class="flex-1 p-4 md:p-8 lg:p-10 animate-fade-in-up">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
            <x-toaster />
        </div>

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-900/40 z-40 lg:hidden backdrop-blur-sm transition-opacity"
            x-transition:enter="duration-300 ease-out" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="duration-200 ease-in"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>
    </div>

    @stack('scripts')
</body>

</html>