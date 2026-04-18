<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Domba Loka - Sistem Manajemen Peternakan</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/logo.png">
    <link rel="apple-touch-icon" href="/assets/logo.png">
    <meta name="msapplication-TileImage" content="/assets/logo.png">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- TomSelect --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Mini Sidebar CSS --}}
    <style>
        /* Sembunyikan ikon sub-menu secara default (saat sidebar terbuka) */
        nav ul i { display: none !important; }

        @media (min-width: 768px) {
            .sidebar-collapsed nav { width: 90px !important; }
            .sidebar-collapsed .md\:ml-64 { margin-left: 90px !important; }
            .sidebar-collapsed nav span, 
            .sidebar-collapsed nav p, 
            .sidebar-collapsed nav button > svg:not(.collapse-icon) { display: none !important; }
            .sidebar-collapsed nav .flex-1.flex-col > .px-5 > .mt-7 { margin-top: 1rem !important; }
            .sidebar-collapsed nav .collapse-icon { transform: rotate(180deg); }
            .sidebar-collapsed nav .justify-between { justify-content: center !important; }
            .sidebar-collapsed nav .gap-3\.5 { gap: 0 !important; }
            .sidebar-collapsed nav .px-4 { padding-left: 0 !important; padding-right: 0 !important; justify-content: center !important; }
            .sidebar-collapsed nav .px-6 { padding-left: 0 !important; padding-right: 0 !important; flex-direction: column; align-items: center; justify-content: center !important; margin-top: 5px; }
            .sidebar-collapsed nav .overflow-hidden { display: none !important; }
            .sidebar-collapsed nav .w-full.flex.items-center.gap-4.p-4 { padding: 0.5rem !important; justify-content: center !important; }
            .sidebar-collapsed nav .ml-1 { margin-left: 0 !important; margin-top: 10px; }
            
            /* Show submenus correctly in mini mode */
            .sidebar-collapsed nav ul { padding-left: 0 !important; }
            .sidebar-collapsed nav ul a { justify-content: center !important; padding-left: 0 !important; padding-right: 0 !important; }
            .sidebar-collapsed nav ul i { display: block !important; margin: 0 auto !important; }
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 transition-all duration-300" x-data="{ showSidebar: false, sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true' }"
    x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebar_collapsed', val))" :class="sidebarCollapsed ? 'sidebar-collapsed' : ''">

    <div class="min-h-screen bg-gray-100">

        {{-- NAVBAR MOBILE --}}
        <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-0 z-50 md:hidden">
            <div class="px-4 h-16 flex items-center justify-between">

                {{-- Tombol Sidebar --}}
                <button @click="showSidebar = true"
                    class="p-3 bg-white border rounded-xl  hover:bg-gray-100 transition">
                    <i data-feather="menu" class="w-6 h-6 text-gray-700"></i>
                </button>

                {{-- Judul Navbar Mobile --}}
                <div class="text-center leading-tight">
                    <h1 class="text-base font-bold text-gray-800">
                        Dashboard Domba Loka
                    </h1>
                </div>

                <a href="{{ route('dashboard') }}" class="p-3 rounded-xl bg-white border hover:bg-gray-100 transition">
                    <i data-feather="home" class="w-5 h-5 text-gray-700"></i>
                </a>

            </div>
        </nav>

        {{-- SIDEBAR --}}
        <div class="md:block" :class="showSidebar ? 'block' : 'hidden'">
            @include('layouts.navigation')
        </div>

        {{-- OVERLAY --}}
        <div x-show="showSidebar" @click="showSidebar=false" class="fixed inset-0 bg-black/40 md:hidden"></div>

        {{-- HEADER DESKTOP --}}
        @isset($header)
            <header class="bg-white shadow md:ml-64 hidden md:block transition-all duration-300">
                <div class="max-w-7xl mx-auto py-6 px-4">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- MAIN CONTENT --}}
        <main>
            <div class="md:ml-64 transition-all duration-300">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- SCRIPTS --}}

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Feather Icons --}}
    <script src="https://unpkg.com/feather-icons"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            feather.replace();
        });
    </script>

    <script>
        window.onload = () => {
            feather.replace();
            if (document.getElementById('preloader')) {
                document.getElementById('preloader').style.display = 'none';
            }
        };
    </script>

    {{-- TomSelect --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    {{-- Alpine + Moment --}}
    <script src="https://unpkg.com/alpinejs" defer></script>

    @isset($scripts)
        {{ $scripts }}
    @endisset

</body>

</html>
