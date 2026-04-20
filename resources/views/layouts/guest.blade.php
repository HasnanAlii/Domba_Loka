<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Domba Loka - Autentikasi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            [x-cloak] { display: none !important; }
            
            .hero-bg {
                background: linear-gradient(to right, rgba(255, 255, 255, 1) 30%, rgba(255, 255, 255, 0.7) 60%, rgba(255, 255, 255, 0.4) 100%), url('/images/hero.png');
                background-size: cover;
                background-position: center;
            }
            .hero-pattern {
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2303235b' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
        </style>
    </head>
    <body class="antialiased text-slate-900 bg-white">
        <div class="min-h-screen relative flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 overflow-hidden hero-bg">
            <div class="absolute inset-0 hero-pattern opacity-30 pointer-events-none"></div>
            
            <!-- Background Decorative Elements -->
            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
                <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-blue-100/50 blur-[120px]"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-[#2ee0a7]/10 blur-[120px]"></div>
            </div>

            <div class="max-w-md w-full relative z-10 space-y-6">
                <!-- Main Auth Card -->
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-8 sm:p-10">
                    
                    <!-- Logo / Header inside the box -->
                    <div class="text-center space-y-5 mb-8">
                        <a href="/" class="inline-block group transition-transform hover:scale-105">
                            <div class="bg-white/90 backdrop-blur-md p-5 rounded-2xl shadow-sm border border-white/50 transition-all group-hover:shadow-md inline-flex items-center justify-center">
                                <x-application-logo class="w-12 h-12 text-[#03235b]" />
                            </div>
                        </a>
                        {{-- <div class="space-y-1">
                            <h1 class="text-[10px] font-black text-[#03235b] uppercase tracking-[0.5em]">Domba Loka Industrial Farm</h1>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-none">Domba Loka Industrial Farm</p>
                        </div> --}}
                    </div>

                    <!-- Slot Content -->
                    {{ $slot }}
                </div>

                <div class="flex flex-col items-center gap-6">
                    <div class="flex items-center gap-8 text-[9px] font-black text-slate-500 uppercase tracking-widest">
                        <a href="/" class="hover:text-[#03235b] transition-colors italic">Beranda</a>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2ee0a7]"></span>
                        <a href="{{ route('public.catalog') }}" class="hover:text-[#03235b] transition-colors italic">Katalog</a>
                    </div>
                    <p class="text-center text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                        &copy; {{ date('Y') }} Domba Loka • Cianjur, Indonesia
                    </p>
                </div>
            </div>
        </div>

        <!-- Feather Icons -->
        <script src="https://unpkg.com/feather-icons"></script>
        <script>
            feather.replace();
        </script>
    </body>
</html>
