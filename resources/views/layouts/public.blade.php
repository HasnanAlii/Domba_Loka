<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Domba Loka - Premium Livestock' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        .hero-gradient {
            background: linear-gradient(to right, #03235b, #0c5197);
        }
    </style>
</head>
<body class="antialiased bg-[#f8fafc] text-slate-900" x-data="{ scrolled: window.pageYOffset > 20 }" @scroll.window="scrolled = window.pageYOffset > 20">
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500 px-6 py-4"
         :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg py-3 border-b border-slate-100' : 'bg-white/50 backdrop-blur-sm py-6'">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="flex items-center gap-4 group">
                    <div class="bg-white p-2 rounded-xl shadow-md border border-slate-100 transform transition-transform group-hover:rotate-3">
                        <x-application-logo class="w-7 h-7 text-[#03235b]" />
                    </div>
                    <span class="text-2xl font-black text-[#03235b] tracking-tight uppercase">Domba Loka</span>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-10">
                <a href="{{ url('/') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-600 hover:text-[#0c5197] transition-colors relative group">
                    Beranda
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#2ee0a7] transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ route('public.catalog') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-[#0c5197] transition-colors relative group">
                    Katalog Domba
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-[#2ee0a7]"></span>
                </a>
                <a href="{{ route('public.about') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-600 hover:text-[#0c5197] transition-colors relative group">
                    Tentang Kami
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#2ee0a7] transition-all group-hover:w-full"></span>
                </a>
            </div>

            <div class="flex items-center gap-5">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="px-6 py-2.5 bg-[#03235b] text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-lg hover:scale-105 transition-all">
                            Area Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-[10px] font-black uppercase tracking-[0.2em] text-[#03235b] hover:opacity-80 transition-opacity">Masuk</a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-2.5 bg-[#2ee0a7] text-[#03235b] font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-lg hover:scale-105 transition-all">
                            Mitra Kami
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main class="min-h-screen pt-24 pb-20">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white pt-32 pb-12 px-6 border-t border-slate-100">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-24">
                <!-- Brand Section -->
                <div class="space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="bg-white p-2 rounded-xl shadow-md border border-slate-100">
                            <x-application-logo class="w-10 h-10 text-[#03235b]" />
                        </div>
                        <span class="text-3xl font-black text-[#03235b] tracking-tighter uppercase">Domba Loka</span>
                    </div>
                    <p class="text-slate-400 font-bold leading-relaxed italic pr-4">
                        "Pusat domba premium Jawa Barat. Fokus pada kualitas, kesehatan, dan kepuasan pelanggan di setiap transaksi."
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-[#03235b] hover:bg-[#03235b] hover:text-white transition-all">
                            <i data-feather="instagram" class="w-4 h-4"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-[#03235b] hover:bg-[#03235b] hover:text-white transition-all">
                            <i data-feather="facebook" class="w-4 h-4"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-[#03235b] hover:bg-[#03235b] hover:text-white transition-all">
                            <i data-feather="youtube" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Navigation Section -->
                <div class="space-y-8">
                    <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">Navigasi</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('public.about') }}" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors flex items-center gap-2 group"><span class="w-1.5 h-1.5 rounded-full bg-[#2ee0a7] scale-0 group-hover:scale-100 transition-transform"></span> Tentang Kami</a></li>
                        <li><a href="{{ route('public.catalog') }}" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors flex items-center gap-2 group"><span class="w-1.5 h-1.5 rounded-full bg-[#2ee0a7] scale-0 group-hover:scale-100 transition-transform"></span> Katalog Domba</a></li>
                        <li><a href="#services" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors flex items-center gap-2 group"><span class="w-1.5 h-1.5 rounded-full bg-[#2ee0a7] scale-0 group-hover:scale-100 transition-transform"></span> Layanan Kami</a></li>
                        <li><a href="#testimonials" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors flex items-center gap-2 group"><span class="w-1.5 h-1.5 rounded-full bg-[#2ee0a7] scale-0 group-hover:scale-100 transition-transform"></span> Testimoni</a></li>
                    </ul>
                </div>

                <!-- Products/Services Section -->
                <div class="space-y-8">
                    <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">Layanan Kami</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors">Hewan Qurban</a></li>
                        <li><a href="#" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors">Paket Aqiqah</a></li>
                        <li><a href="#" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors">Suplai Restoran</a></li>
                        <li><a href="#" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors">Kemitraan Ternak</a></li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="space-y-8">
                    <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">Kontak Kami</h4>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#2ee0a7] shrink-0">
                                <i data-feather="map-pin" class="w-4 h-4"></i>
                            </div>
                            <p class="text-[11px] font-bold text-slate-600 leading-relaxed uppercase tracking-wider mt-1">
                                Kec. Pacet, Cianjur,<br>Jawa Barat 43253
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#2ee0a7] shrink-0">
                                <i data-feather="phone" class="w-4 h-4"></i>
                            </div>
                            <p class="text-[11px] font-black text-[#03235b] uppercase tracking-widest">
                                +62 812 3456 7890
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#2ee0a7] shrink-0">
                                <i data-feather="mail" class="w-4 h-4"></i>
                            </div>
                            <p class="text-[11px] font-black text-[#03235b] uppercase tracking-widest">
                                hello@dombaloka.com
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-12 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6 text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">
                 <p>&copy; {{ date('Y') }} Domba Loka Farm • Cianjur, Jawa Barat</p>
                 <div class="flex items-center gap-8">
                    <a href="#" class="hover:text-[#03235b] transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-[#03235b] transition-colors">Kebijakan Privasi</a>
                 </div>
            </div>
        </div>
    </footer>
</body>
</html>
