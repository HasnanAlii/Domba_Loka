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
            
            .bg-grid-pattern {
                background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
                background-size: 24px 24px;
            }
        </style>
    </head>
    <body class="antialiased text-slate-900 bg-[#f8fafc]">
        <div class="min-h-screen relative flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
            
            <!-- Background Decorative Elements -->
            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 bg-grid-pattern opacity-[0.4]"></div>
                <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-blue-100/50 blur-[120px]"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-[#2ee0a7]/10 blur-[120px]"></div>
            </div>

            <div class="max-w-md w-full space-y-10 relative z-10">
                <div class="text-center space-y-6">
                    <a href="/" class="inline-block group transition-transform hover:scale-105">
                        <div class="bg-white p-6 rounded-[2.5rem] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] border border-slate-50 transition-all group-hover:shadow-[0_40px_80px_-15px_rgba(3,35,91,0.2)]">
                            <x-application-logo class="w-14 h-14 text-[#03235b]" />
                        </div>
                    </a>
                    <div class="space-y-1">
                        <h1 class="text-xs font-black text-[#03235b] uppercase tracking-[0.5em]">Portal Manajemen</h1>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Domba Loka Industrial Farm</p>
                    </div>
                </div>

                <div class="bg-white rounded-[3.5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.08)] border border-white p-10 sm:p-12">
                    {{ $slot }}
                </div>

                <div class="flex flex-col items-center gap-6">
                    <div class="flex items-center gap-8 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                        <a href="/" class="hover:text-[#03235b] transition-colors italic">Beranda</a>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2ee0a7]"></span>
                        <a href="{{ route('public.catalog') }}" class="hover:text-[#03235b] transition-colors italic">Katalog</a>
                    </div>
                    <p class="text-center text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">
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
