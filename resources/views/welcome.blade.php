<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Domba Loka - Pusat Domba Premium & Unggul</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }
        .hero-bg {
            background: linear-gradient(to right, rgba(255, 255, 255, 1) 30%, rgba(255, 255, 255, 0.7) 60%, rgba(255, 255, 255, 0.4) 100%), url('/images/hero.png');
            background-size: cover;
            background-position: center;
        }
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2303235b' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .emerald-glow {
            box-shadow: 0 0 20px rgba(46, 224, 167, 0.3);
        }
        [x-cloak] { display: none !important; }
        .text-gradient {
            background: linear-gradient(to right, #03235b, #1c88da);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .floating-anim {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="antialiased bg-white text-slate-900 overflow-x-hidden" x-data="{ scrolled: false, mobileMenu: false }" @scroll.window="scrolled = window.pageYOffset > 20">
    
    <!-- Navigasi -->
    <nav class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500 px-6 py-4"
         :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg py-3 border-b border-slate-100' : 'bg-transparent py-6'">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-white p-2 rounded-xl shadow-md border border-slate-100 transform transition-transform hover:rotate-3">
                    <x-application-logo class="w-7 h-7 text-[#03235b]" />
                </div>
                <span class="text-2xl font-black transition-colors tracking-tight uppercase"
                      :class="scrolled ? 'text-[#03235b]' : 'text-[#03235b]'">Domba Loka</span>
            </div>

            <div class="hidden md:flex items-center gap-10">
                <a href="{{ route('public.about') }}" class="text-xs font-black uppercase tracking-[0.2em] text-slate-600 hover:text-[#0c5197] transition-colors relative group">
                    Tentang Kami
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#2ee0a7] transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ route('public.catalog') }}" class="text-xs font-black uppercase tracking-[0.2em] text-slate-600 hover:text-[#0c5197] transition-colors relative group">
                    Katalog Domba
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#2ee0a7] transition-all group-hover:w-full"></span>
                </a>
                <a href="#services" class="text-xs font-black uppercase tracking-[0.2em] text-slate-600 hover:text-[#0c5197] transition-colors relative group">
                    Layanan Kami
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#2ee0a7] transition-all group-hover:w-full"></span>
                </a>
                <a href="#faq" class="text-xs font-black uppercase tracking-[0.2em] text-slate-600 hover:text-[#0c5197] transition-colors relative group">
                    FAQ
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
                           class="text-xs font-black uppercase tracking-[0.2em] text-[#03235b] hover:opacity-80 transition-opacity">Masuk</a>
                        
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="hidden sm:block px-6 py-2.5 bg-[#2ee0a7] text-[#03235b] font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-xl hover:scale-105 transition-all emerald-glow">
                                Bermitra
                            </a>
                        @endif
                    @endauth
                @endif
                
                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden w-10 h-10 flex flex-col items-center justify-center gap-1.5 focus:outline-none">
                    <span class="w-6 h-0.5 bg-[#03235b] transition-all transform" :class="mobileMenu ? 'rotate-45 translate-y-2' : ''"></span>
                    <span class="w-6 h-0.5 bg-[#03235b] transition-all" :class="mobileMenu ? 'opacity-0' : ''"></span>
                    <span class="w-6 h-0.5 bg-[#03235b] transition-all transform" :class="mobileMenu ? '-rotate-45 -translate-y-2' : ''"></span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-full"
             class="fixed inset-0 z-[150] bg-white pt-32 px-10 md:hidden" 
             x-cloak>
            <div class="space-y-12">
                <a @click="mobileMenu = false" href="{{ route('public.about') }}" class="block text-4xl font-black text-[#03235b] tracking-tighter uppercase italic">Tentang Kami</a>
                <a @click="mobileMenu = false" href="{{ route('public.catalog') }}" class="block text-4xl font-black text-[#03235b] tracking-tighter uppercase italic">Katalog</a>
                <a @click="mobileMenu = false" href="#services" class="block text-4xl font-black text-[#03235b] tracking-tighter uppercase italic">Layanan</a>
                <a @click="mobileMenu = false" href="#faq" class="block text-4xl font-black text-[#03235b] tracking-tighter uppercase italic">FAQ</a>
                
                <div class="pt-12 border-t border-slate-100 flex flex-col gap-6">
                    @guest
                        <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-widest text-slate-400">Masuk Akun</a>
                        <a href="{{ route('register') }}" class="w-full py-5 bg-[#2ee0a7] text-[#03235b] text-center font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl">Daftar Kemitraan</a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="w-full py-5 bg-[#03235b] text-white text-center font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl">Ke Dashboard</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden hero-bg">
        <div class="absolute inset-0 hero-pattern opacity-30"></div>
        
        <div class="relative max-w-7xl mx-auto px-6 w-full py-20">
            <div class="max-w-3xl space-y-10">
                {{-- <div class="inline-flex items-center gap-4 px-5 py-2.5 bg-white/80 backdrop-blur-md rounded-2xl border border-slate-100 shadow-sm">
                    <span class="w-2.5 h-2.5 bg-[#2ee0a7] rounded-full emerald-glow animate-pulse"></span>
                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-[0.3em]">Penyedia Domba Premium Jawa Barat</span>
                </div> --}}
                
                <h1 class="text-7xl lg:text-8xl font-black text-[#03235b] leading-[0.95] tracking-tighter">
                    DOMBA<br>
                    <span class="text-gradient underline decoration-[#2ee0a7] decoration-8 underline-offset-[12px]">UNGGULAN.</span>
                </h1>
                
                <p class="text-xl text-slate-600 font-bold leading-relaxed max-w-xl border-l-4 border-[#2ee0a7] pl-8">
                    Menyediakan bibit unggul dan domba siap potong dengan kualitas daging terbaik. Setiap ekor dipantau kesehatannya secara ketat untuk jaminan kepuasan Anda.
                </p>

                <div class="flex flex-wrap items-center gap-6 pt-4">
                    <a href="{{ route('public.catalog') }}" class="group relative px-10 py-5 bg-[#03235b] text-white font-black text-xs uppercase tracking-[0.3em] rounded-2xl shadow-2xl transition-all hover:translate-y-[-4px]">
                        Pilih Domba Sekarang
                    </a>
                    <a href="https://wa.me/+6287708463586" target="_blank" class="px-10 py-5 bg-white text-[#03235b] border border-slate-200 font-black text-xs uppercase tracking-[0.3em] rounded-2xl hover:bg-slate-50 transition-all flex items-center gap-4 group">
                        Konsultasi Pesanan
                        <svg class="w-5 h-5 text-[#2ee0a7] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="3" /></svg>
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-12 pt-16 border-t border-slate-200">
                    <div class="space-y-1">
                        <p class="text-4xl font-black text-[#03235b] tracking-tighter">100%</p>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Domba Sehat</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-4xl font-black text-[#03235b] tracking-tighter">Terjamin</p>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Bobot Timbangan</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-4xl font-black text-[#03235b] tracking-tighter">Siap</p>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Kirim Se-Jabar</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Farm Experience Section -->
    <section id="about" class="py-32 px-6 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-24 items-center">
                <div class="relative">
                    <div class="absolute -top-12 -left-12 w-64 h-64 bg-[#2ee0a7]/10 rounded-full blur-3xl"></div>
                    <div class="relative rounded-[4rem] overflow-hidden shadow-2xl transform -rotate-2 hover:rotate-0 transition-transform duration-700">
                        <img src="{{ asset('images/farm_panorama.png') }}" alt="Domba Loka Farm" class="w-full h-[600px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#03235b]/60 to-transparent"></div>
                        <div class="absolute bottom-12 left-12 right-12">
                            <p class="text-white/80 font-bold uppercase tracking-[0.3em] text-[10px] mb-2">Lokasi Kami</p>
                            <h4 class="text-3xl font-black text-white tracking-tight">Desa Galudra, Kec Cugenang, Cianjur.</h4>
                        </div>
                    </div>
                </div>
                <div class="space-y-12">
                    <div class="space-y-6">
                        <div class="inline-flex items-center gap-3 mb-6">
                            <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                            <h2 class="text-[10px] font-black text-[#0c5197] uppercase tracking-[0.5em]">Warisan & Kualitas</h2>
                        </div>
                        <h3 class="text-6xl font-black text-[#03235b] tracking-tighter leading-[0.95]">Peternakan Modern dengan <span class="text-[#2ee0a7]">Hati.</span></h3>
                        <p class="text-xl text-slate-500 font-medium leading-relaxed">
                            Berawal dari kecintaan pada peternakan lokal, Domba Loka hadir dengan standar manajemen modern. Kami memadukan teknologi pemantauan kesehatan dengan pakan organik terbaik dari alam Cianjur yang asri.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-[#03235b]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-width="2.5"/></svg>
                            </div>
                            <h5 class="text-lg font-black text-[#03235b]">Hygienic Barn</h5>
                            <p class="text-sm text-slate-400 font-bold">Kandang yang selalu bersih untuk memastikan domba bebas stres dan penyakit.</p>
                        </div>
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-[#03235b]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2.5"/></svg>
                            </div>
                            <h5 class="text-lg font-black text-[#03235b]">Fast Response</h5>
                            <p class="text-sm text-slate-400 font-bold">Tim ahli kami siap melayani konsultasi pemilihan domba setiap saat.</p>
                        </div>
                    </div>

                    <a href="{{ route('public.about') }}" class="inline-flex items-center gap-4 text-xs font-black text-[#03235b] uppercase tracking-[0.3em] group">
                        Selengkapnya Tentang Kami
                        <span class="w-12 h-px bg-[#2ee0a7] group-hover:w-20 transition-all"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Katalog Section -->
    <section id="catalog" class="py-32 px-6 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-8">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-3 mb-2">
                        <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                        <h2 class="text-[10px] font-black text-[#0c5197] uppercase tracking-[0.5em]">Katalog Domba</h2>
                    </div>
                    <h3 class="text-6xl font-black text-[#03235b] tracking-tighter">Pilihan Domba Terbaik.</h3>
                </div>
                <a href="{{ route('public.catalog') }}" class="text-xs font-black text-slate-400 uppercase tracking-widest hover:text-[#0c5197] transition-colors flex items-center gap-3">
                    Lihat Semua Domba
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="3" /></svg>
                </a>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                @forelse($featuredSheep as $sheep)
                    <div class="group relative bg-white rounded-[3rem] overflow-hidden shadow-xl border border-slate-100 hover:shadow-2xl transition-all duration-500">
                        <div class="h-[450px] overflow-hidden bg-slate-100 relative">
                            @if($sheep->photo)
                                <img src="{{ asset('storage/' . $sheep->photo) }}" alt="{{ $sheep->code }}" class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-application-logo class="w-20 h-20 text-slate-200" />
                                </div>
                            @endif
                            <div class="absolute top-6 left-6">
                                {{-- <span class="px-4 py-2 bg-white/90 backdrop-blur text-[#03235b] text-[10px] font-black uppercase tracking-widest rounded-xl shadow-sm border border-white/20">
                                    {{ $sheep->code }}
                                </span> --}}
                            </div>
                        </div>
                        <div class="p-12 flex justify-between items-center bg-white">
                            <div class="space-y-4">
                                <span class="px-3 py-1 bg-white/80 backdrop-blur-md text-[#03235b] text-[9px] font-black uppercase tracking-widest rounded-lg border border-white/50 shadow-sm">
                                     {{ $sheep->code }}
                                 </span>
                                 <a href="{{ route('public.catalog.show', $sheep->code) }}" class="block group/link">
                                    <h4 class="text-4xl font-black text-[#03235b] tracking-tight group-hover/link:text-[#0c5197] transition-colors">
                                      Domba  {{($sheep->sheepType->name) }}
                                    </h4>
                                 </a>
                            </div>
                            <div class="text-right">
                                 <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Harga</p>
                                 <p class="text-3xl font-black text-[#03235b]">Rp {{ number_format($sheep->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200 text-center">
                        <p class="text-slate-400 font-bold">Belum ada domba di katalog unggulan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="py-32 px-6 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="text-center space-y-4 mb-24">
                <div class="inline-flex items-center gap-3 mb-2">
                    <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                    <h2 class="text-[10px] font-black text-[#0c5197] uppercase tracking-[0.5em]">Cara Memulai</h2>
                    <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                </div>
                <h3 class="text-6xl font-black text-[#03235b] tracking-tighter">Proses Transaksi <span class="italic underline decoration-[#2ee0a7]">Tanpa Ribet.</span></h3>
            </div>

            <div class="grid md:grid-cols-4 gap-12 relative">
                <!-- Line connection -->
                <div class="hidden md:block absolute top-1/4 left-0 right-0 h-px bg-slate-100 z-0"></div>
                
                <div class="relative z-10 text-center space-y-8 group">
                    <div class="w-24 h-24 bg-white shadow-2xl rounded-[2rem] flex items-center justify-center mx-auto border border-slate-50 group-hover:bg-[#2ee0a7] group-hover:scale-110 transition-all duration-500">
                        <span class="text-3xl font-black text-[#03235b]">01</span>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-[#03235b] mb-4">Pilih Domba</h4>
                        <p class="text-sm text-slate-400 font-bold leading-relaxed px-4">Telusuri katalog lengkap kami dengan foto dan spesifikasi detail.</p>
                    </div>
                </div>

                <div class="relative z-10 text-center space-y-8 group">
                    <div class="w-24 h-24 bg-white shadow-2xl rounded-[2rem] flex items-center justify-center mx-auto border border-slate-50 group-hover:bg-[#2ee0a7] group-hover:scale-110 transition-all duration-500">
                        <span class="text-3xl font-black text-[#03235b]">02</span>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-[#03235b] mb-4">Konsultasi</h4>
                        <p class="text-sm text-slate-400 font-bold leading-relaxed px-4">Hubungi tim kami via WhatsApp untuk detail kesehatan & video terbaru.</p>
                    </div>
                </div>

                <div class="relative z-10 text-center space-y-8 group">
                    <div class="w-24 h-24 bg-white shadow-2xl rounded-[2rem] flex items-center justify-center mx-auto border border-slate-50 group-hover:bg-[#2ee0a7] group-hover:scale-110 transition-all duration-500">
                        <span class="text-3xl font-black text-[#03235b]">03</span>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-[#03235b] mb-4">Pembayaran</h4>
                        <p class="text-sm text-slate-400 font-bold leading-relaxed px-4">Transaksi aman melalui transfer bank atau bayar di tempat (COD).</p>
                    </div>
                </div>

                <div class="relative z-10 text-center space-y-8 group">
                    <div class="w-24 h-24 bg-[#03235b] shadow-2xl rounded-[2rem] flex items-center justify-center mx-auto text-white group-hover:scale-110 transition-all duration-500">
                        <svg class="w-10 h-10 text-[#2ee0a7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" /></svg>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-[#03235b] mb-4">Pengiriman</h4>
                        <p class="text-sm text-slate-400 font-bold leading-relaxed px-4">Domba dikirim dengan armada khusus langsung ke lokasi Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section id="features" class="py-32 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 mb-20 text-center">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                <h2 class="text-[10px] font-black text-[#0c5197] uppercase tracking-[0.5em]">Keunggulan Kami</h2>
                <div class="w-10 h-1 bg-[#2ee0a7]"></div>
            </div>
            <h3 class="text-6xl font-black text-[#03235b] tracking-tighter">Mengapa Memilih <span class="text-gradient">Domba Loka?</span></h3>
        </div>
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-4 gap-8">
            <div class="p-12 bg-slate-50 border border-slate-100 rounded-[3rem] group hover:bg-white hover:shadow-2xl transition-all">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm border border-slate-100 group-hover:bg-[#03235b] transition-all">
                    <svg class="w-8 h-8 text-[#03235b] group-hover:text-[#2ee0a7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h6l3 3h5l1 5H5V8zm0 5l-1 2m12-2l1 2M8 18a2 2 0 100-4 2 2 0 000 4zm10 0a2 2 0 100-4 2 2 0 000 4zM2 8h2m-2 4h2m-2 4h2" />
                    </svg>
                </div>
                <h4 class="text-lg font-black text-[#03235b] uppercase tracking-widest mb-4">Pengiriman Cepat</h4>
                <p class="text-sm font-bold text-slate-400 leading-relaxed">Kami antar domba pesanan Anda sampai ke depan rumah dengan armada khusus ternak.</p>
            </div>
            <div class="p-12 bg-slate-50 border border-slate-100 rounded-[3rem] group hover:bg-white hover:shadow-2xl transition-all">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm border border-slate-100 group-hover:bg-[#03235b] transition-all">
                    <svg class="w-8 h-8 text-[#03235b] group-hover:text-[#2ee0a7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h4 class="text-lg font-black text-[#03235b] uppercase tracking-widest mb-4">Sehat & Bergaransi</h4>
                <p class="text-sm font-bold text-slate-400 leading-relaxed">Seluruh domba telah divaksinasi dan kami garansi kesehatannya hingga tiba di lokasi.</p>
            </div>
            <div class="p-12 bg-slate-50 border border-slate-100 rounded-[3rem] group hover:bg-white hover:shadow-2xl transition-all">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm border border-slate-100 group-hover:bg-[#03235b] transition-all">
                    <svg class="w-8 h-8 text-[#03235b] group-hover:text-[#2ee0a7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <h4 class="text-lg font-black text-[#03235b] uppercase tracking-widest mb-4">Timbangan Akurat</h4>
                <p class="text-sm font-bold text-slate-400 leading-relaxed">Sistem jual beli transparan dengan timbangan yang akurat dan bisa dipantau langsung.</p>
            </div>
            <div class="p-12 bg-[#03235b] shadow-2xl rounded-[3rem] group hover:scale-105 transition-all text-white">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-8 border border-white/10">
                    <svg class="w-8 h-8 text-[#2ee0a7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <h4 class="text-lg font-black uppercase tracking-widest mb-4">Pakan Organik</h4>
                <p class="text-sm font-bold text-blue-100/50 leading-relaxed">Pakan berkualitas tinggi tanpa bahan kimia untuk menghasilkan daging yang sehat dan gurih.</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-32 px-6 bg-[#f0f4f8]">
        <div class="max-w-7xl mx-auto space-y-20">
            <div class="text-center space-y-4">
                <div class="inline-flex items-center gap-3 mb-2">
                    <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                    <h2 class="text-[10px] font-black text-[#0c5197] uppercase tracking-[0.5em]">Kualitas & Layanan</h2>
                    <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                </div>
                <h3 class="text-6xl font-black text-[#03235b] tracking-tighter uppercase">Layanan Kami.</h3>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-12 bg-white rounded-[3rem] border border-slate-100 shadow-xl group hover:-translate-y-3 transition-all duration-500">
                    <p class="text-6xl font-black text-slate-100 group-hover:text-[#2ee0a7]/20 transition-colors mb-8">01</p>
                    <h4 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Aqiqah & Qurban</h4>
                    <p class="text-slate-500 font-medium leading-relaxed mb-10">Paket ibadah lengkap mulai dari pemilihan domba, pemotongan syar'i, hingga penyaluran daging.</p>
                    <div class="w-12 h-1 bg-slate-100 group-hover:bg-[#2ee0a7] transition-all w-0 group-hover:w-full"></div>
                </div>
                <div class="p-12 bg-white rounded-[3rem] border border-slate-100 shadow-xl group hover:-translate-y-3 transition-all duration-500">
                    <p class="text-6xl font-black text-slate-100 group-hover:text-[#2ee0a7]/20 transition-colors mb-8">02</p>
                    <h4 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Suplai Restoran</h4>
                    <p class="text-slate-500 font-medium leading-relaxed mb-10">Penyedia karkas domba segar berkualitas tinggi untuk kebutuhan hotel, restoran, dan katering.</p>
                    <div class="w-12 h-1 bg-slate-100 group-hover:bg-[#2ee0a7] transition-all w-0 group-hover:w-full"></div>
                </div>
                <div class="p-12 bg-[#03235b] rounded-[3rem] shadow-2xl group hover:-translate-y-3 transition-all duration-500">
                    <p class="text-6xl font-black text-white/5 mb-8">03</p>
                    <h4 class="text-3xl font-black text-white mb-4 tracking-tight">Bibit Unggul</h4>
                    <p class="text-blue-100/60 font-medium leading-relaxed mb-10">Menyediakan bibit domba Garut dan jenis lainnya dengan genetika juara untuk peternak.</p>
                    <div class="w-12 h-1 bg-white/10 group-hover:bg-[#2ee0a7] transition-all w-0 group-hover:w-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-32 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col items-center text-center space-y-6 mb-24">
                <div class="inline-flex items-center gap-3 mb-2">
                    <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                    <h2 class="text-[10px] font-black text-[#0c5197] uppercase tracking-[0.5em]">Kesan Pelanggan</h2>
                    <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                </div>
                <div class="px-6 py-2 bg-slate-50 rounded-full border border-slate-100 flex items-center gap-3">
                    <div class="flex -space-x-3">
                        <img src="{{ asset('images/avatar1.png') }}" class="w-8 h-8 rounded-full border-2 border-white object-cover">
                        <img src="{{ asset('images/avatar2.png') }}" class="w-8 h-8 rounded-full border-2 border-white object-cover">
                        <div class="w-8 h-8 rounded-full bg-[#03235b] border-2 border-white flex items-center justify-center text-[10px] text-white font-black">+2k</div>
                    </div>
                    <span class="text-[10px] font-black text-[#03235b] uppercase tracking-widest">Dipercaya 2000+ Pelanggan</span>
                </div>
                <h3 class="text-6xl font-black text-[#03235b] tracking-tighter uppercase italic underline decoration-[#2ee0a7] decoration-[8px] underline-offset-[12px]">SUARA MEREKA.</h3>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-12 bg-slate-50 rounded-[3rem] border border-slate-100 space-y-8 hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                    <div class="flex gap-1 text-[#2ee0a7]">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    </div>
                    <p class="text-xl font-bold text-[#03235b] italic leading-relaxed">
                        "Domba di sini sangat sehat dan terawat. Saya pesan untuk Aqiqah anak, prosesnya sangat mudah dan dokumentasi pemotongannya lengkap. Sangat puas!"
                    </p>
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/avatar1.png') }}" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <p class="font-black text-[#03235b] uppercase tracking-widest text-[11px]">Bapak Hendra</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pengusaha - Bandung</p>
                        </div>
                    </div>
                </div>

                <div class="p-12 bg-[#03235b] rounded-[3rem] shadow-2xl space-y-8 transform md:-translate-y-6">
                    <div class="flex gap-1 text-[#2ee0a7]">
                         @for($i=0; $i<5; $i++)
                         <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                         @endfor
                    </div>
                    <p class="text-xl font-bold text-white italic leading-relaxed">
                        "Layanan suplai karkas untuk restoran saya sangat konsisten. Timbangan akurat dan pengiriman selalu tepat waktu. Domba Loka partner terbaik kami."
                    </p>
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/avatar2.png') }}" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <p class="font-black text-white uppercase tracking-widest text-[11px]">Ibu Sari</p>
                            <p class="text-[9px] font-bold text-blue-200/50 uppercase tracking-widest">Pemilik Resto - Cianjur</p>
                        </div>
                    </div>
                </div>

                <div class="p-12 bg-slate-50 rounded-[3rem] border border-slate-100 space-y-8 hover:bg-white hover:shadow-2xl transition-all duration-500 group">
                    <div class="flex gap-1 text-[#2ee0a7]">
                        @for($i=0; $i<5; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        @endfor
                    </div>
                    <p class="text-xl font-bold text-[#03235b] italic leading-relaxed">
                        "Bibit domba Garut yang saya beli di sini memiliki genetika luar biasa. Pertumbuhannya sangat cepat. Konsultasinya juga sangat membantu."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-[#2ee0a7] flex items-center justify-center font-black text-[#03235b]">AM</div>
                        <div>
                            <p class="font-black text-[#03235b] uppercase tracking-widest text-[11px]">Anwar Mushaddad</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Peternak - Sukabumi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-32 px-6 bg-[#f8fafc]" x-data="{ active: 0 }">
        <div class="max-w-4xl mx-auto">
            <div class="text-center space-y-4 mb-20">
                <div class="inline-flex items-center gap-3 mb-2">
                    <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                    <h2 class="text-[10px] font-black text-[#0c5197] uppercase tracking-[0.5em]">Tanya Jawab</h2>
                    <div class="w-10 h-1 bg-[#2ee0a7]"></div>
                </div>
                <h3 class="text-6xl font-black text-[#03235b] tracking-tighter">Hal yang Sering <span class="text-[#2ee0a7]">Ditanyakan.</span></h3>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <button @click="active = (active === 1 ? 0 : 1)" class="w-full p-8 flex items-center justify-between text-left">
                        <span class="text-lg font-black text-[#03235b] uppercase tracking-tight">Apakah domba bisa dikirim ke luar Jawa Barat?</span>
                        <svg class="w-6 h-6 transition-transform duration-500" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"/></svg>
                    </button>
                    <div x-show="active === 1" x-collapse>
                        <div class="px-8 pb-8 text-slate-500 font-medium leading-relaxed uppercase tracking-wider text-[11px]">
                            Saat ini fokus utama pengiriman kami adalah wilayah Jawa Barat (Jabodetabek, Bandung, Cianjur, dll) menggunakan armada sendiri. Namun untuk pemesanan bibit unggul dalam jumlah banyak, kami bisa melayani pengiriman ke luar provinsi dengan jasa ekspedisi hewan mitra kami.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <button @click="active = (active === 2 ? 0 : 2)" class="w-full p-8 flex items-center justify-between text-left">
                        <span class="text-lg font-black text-[#03235b] uppercase tracking-tight">Apakah Domba Loka menyediakan paket Aqiqah siap saji?</span>
                        <svg class="w-6 h-6 transition-transform duration-500" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"/></svg>
                    </button>
                    <div x-show="active === 2" x-collapse>
                        <div class="px-8 pb-8 text-slate-500 font-medium leading-relaxed uppercase tracking-wider text-[11px]">
                            Ya, kami bekerja sama dengan katering profesional untuk menyediakan paket Aqiqah siap saji dalam bentuk nasi kotak atau prasmanan. Anda tetap bisa memilih dombanya secara langsung atau via video call.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <button @click="active = (active === 3 ? 0 : 3)" class="w-full p-8 flex items-center justify-between text-left">
                        <span class="text-lg font-black text-[#03235b] uppercase tracking-tight">Bagaimana sistem garansi kesehatan di Domba Loka?</span>
                        <svg class="w-6 h-6 transition-transform duration-500" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3"/></svg>
                    </button>
                    <div x-show="active === 3" x-collapse>
                        <div class="px-8 pb-8 text-slate-500 font-medium leading-relaxed uppercase tracking-wider text-[11px]">
                            Setiap domba yang keluar dari farm kami telah melalui pengecekan kesehatan terakhir. Kami memberikan garansi ganti unit jika domba sakit atau mati dalam perjalanan hingga tiba di lokasi pelanggan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-32 px-6 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto rounded-[4rem] bg-[#03235b] p-24 text-center relative shadow-[0_40px_80px_-20px_rgba(3,35,91,0.4)]">
             <div class="absolute inset-0 hero-pattern opacity-10"></div>
             <div class="relative z-10 space-y-12">
                <h2 class="text-6xl lg:text-7xl font-black text-white tracking-tighter leading-none uppercase italic underline decoration-[#2ee0a7] decoration-[12px] underline-offset-[16px]">DAPATKAN DOMBA<br>TERBAIK ANDA.</h2>
                <div class="flex flex-wrap justify-center gap-8 pt-12">
                    <a href="{{ route('public.catalog') }}" class="px-14 py-7 bg-white text-[#03235b] font-black text-xs uppercase tracking-[0.4em] rounded-2xl shadow-xl hover:scale-105 transition-all">Lihat Katalog</a>
                    <a href="https://wa.me/+6287708463586" class="px-14 py-7 bg-transparent text-white border-2 border-white/20 font-black text-xs uppercase tracking-[0.4em] rounded-2xl hover:bg-white/5 transition-all">Pesan Lewat WA</a>
                </div>
             </div>
        </div>
    </section>

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
                                +62 877 0846 3586
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

    <!-- Floating Elements -->
    <div class="fixed bottom-10 right-10 z-[200] flex flex-col gap-4 items-end" x-data="chatbot()">
        <!-- Chatbot Window -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-10 scale-95"
             class="w-[380px] h-[550px] bg-white rounded-[2.5rem] shadow-[0_20px_60px_-15px_rgba(3,35,91,0.3)] border border-slate-100 flex flex-col overflow-hidden mb-4" 
             x-cloak>
            <!-- Header -->
            <div class="bg-[#03235b] p-6 text-white flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-[#2ee0a7] rounded-xl flex items-center justify-center text-[#03235b]">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="M9,15a1,1,0,1,0,1,1A1,1,0,0,0,9,15ZM2,14a1,1,0,0,0-1,1v2a1,1,0,0,0,2,0V15A1,1,0,0,0,2,14Zm20,0a1,1,0,0,0-1,1v2a1,1,0,0,0,2,0V15A1,1,0,0,0,22,14ZM17,7H13V5.72A2,2,0,0,0,14,4a2,2,0,0,0-4,0,2,2,0,0,0,1,1.72V7H7a3,3,0,0,0-3,3v9a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V10A3,3,0,0,0,17,7ZM13.72,9l-.5,2H10.78l-.5-2ZM18,19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V10A1,1,0,0,1,7,9H8.22L9,12.24A1,1,0,0,0,10,13h4a1,1,0,0,0,1-.76L15.78,9H17a1,1,0,0,1,1,1Zm-3-4a1,1,0,1,0,1,1A1,1,0,0,0,15,15Z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-widest">LokaAI</h4>
                        <p class="text-[9px] font-bold text-[#2ee0a7] uppercase tracking-[0.2em]">Asisten Virtual</p>
                    </div>
                </div>
                <button @click="isOpen = false" class="text-white/50 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5"/></svg>
                </button>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/50" x-ref="messagesBox">
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.role === 'user' ? 'bg-[#03235b] text-white rounded-2xl rounded-tr-none px-5 py-3 max-w-[80%] shadow-lg' : 'bg-white text-slate-800 rounded-2xl rounded-tl-none px-5 py-3 max-w-[80%] shadow-md border border-slate-100'">
                            <p class="text-sm font-medium leading-relaxed" x-text="msg.text"></p>
                        </div>
                    </div>
                </template>
                <div x-show="isLoading" class="flex justify-start">
                    <div class="bg-white rounded-2xl rounded-tl-none px-5 py-3 shadow-md border border-slate-100 flex gap-1">
                        <span class="w-1.5 h-1.5 bg-slate-300 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-slate-300 rounded-full animate-bounce [animation-delay:-0.15s]"></span>
                        <span class="w-1.5 h-1.5 bg-slate-300 rounded-full animate-bounce [animation-delay:-0.3s]"></span>
                    </div>
                </div>
            </div>

            <!-- Footer / Input -->
            <div class="p-6 bg-white border-t border-slate-100">
                <form @submit.prevent="sendMessage()" class="flex gap-2">
                    <input type="text" 
                           x-model="input" 
                           placeholder="Tanya LokaAI..." 
                           class="flex-1 px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-[#2ee0a7] transition-all">
                    <button type="submit" 
                            :disabled="isLoading || !input.trim()"
                            class="w-12 h-12 bg-[#03235b] text-white rounded-2xl flex items-center justify-center hover:bg-[#0c5197] disabled:opacity-50 transition-all">
                        <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-width="2.5" /></svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex flex-col gap-4 items-end">
            <!-- Chatbot Bubble -->
            <button @click="isOpen = !isOpen" class="group relative">
                <div class="absolute -inset-4 bg-[#03235b]/20 rounded-full blur-xl group-hover:bg-[#03235b]/40 transition-all" :class="isOpen ? 'opacity-0' : 'opacity-100 animate-pulse'"></div>
                <div class="relative w-16 h-16 bg-[#03235b] text-white rounded-full flex items-center justify-center shadow-2xl transform transition-transform group-hover:scale-110">
                    <svg x-show="!isOpen" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" stroke-width="2.5"/></svg>
                    <svg x-show="isOpen" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3"/></svg>
                </div>
            </button>

            <!-- Scroll To Top -->
            <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
                    x-show="scrolled"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-10"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-10"
                    class="w-16 h-16 bg-white border border-slate-100 text-[#03235b] rounded-full flex items-center justify-center shadow-2xl hover:bg-slate-50 transition-all group"
                    x-cloak>
                <svg class="w-6 h-6 transform group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7" stroke-width="3" /></svg>
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function chatbot() {
            return {
                isOpen: false,
                input: '',
                messages: [
                    { role: 'assistant', text: 'Halo! Saya LokaAI. Ada yang bisa saya bantu terkait domba-domba koleksi kami hari ini?' }
                ],
                isLoading: false,
                async sendMessage() {
                    if (!this.input.trim()) return;
                    
                    const userText = this.input;
                    this.messages.push({ role: 'user', text: userText });
                    this.input = '';
                    this.isLoading = true;
                    
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });

                    try {
                        const response = await fetch('/chatbot', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ message: userText })
                        });

                        const data = await response.json();
                        this.messages.push({ role: 'assistant', text: data.reply });
                    } catch (error) {
                        this.messages.push({ role: 'assistant', text: 'Maaf, sepertinya sedang ada gangguan teknis. Hubungi kami via WhatsApp saja ya!' });
                    } finally {
                        this.isLoading = false;
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                    }
                },
                scrollToBottom() {
                    const box = this.$refs.messagesBox;
                    box.scrollTop = box.scrollHeight;
                }
            }
        }
    </script>
    <!-- Feather Icons Init -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>
