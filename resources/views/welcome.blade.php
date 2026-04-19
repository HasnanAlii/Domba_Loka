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
    </style>
</head>
<body class="antialiased bg-white text-slate-900 overflow-x-hidden" x-data="{ scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 20">
    
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
                               class="px-6 py-2.5 bg-[#2ee0a7] text-[#03235b] font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-xl hover:scale-105 transition-all emerald-glow">
                                Bermitra
                            </a>
                        @endif
                    @endauth
                @endif
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
                    <a href="https://wa.me/6281234567890" target="_blank" class="px-10 py-5 bg-white text-[#03235b] border border-slate-200 font-black text-xs uppercase tracking-[0.3em] rounded-2xl hover:bg-slate-50 transition-all flex items-center gap-4 group">
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

    <!-- Katalog Section -->
    <section id="catalog" class="py-32 px-6 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-1 bg-[#2ee0a7]"></div>
                        <h2 class="text-xs font-black text-[#0c5197] uppercase tracking-[0.4em]">Katalog Domba</h2>
                    </div>
                    <h3 class="text-5xl font-black text-[#03235b] tracking-tighter">Pilihan Domba Terbaik.</h3>
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

    <!-- Keunggulan Section -->
    <section id="about" class="py-32 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-4 gap-8">
            <div class="p-12 bg-slate-50 border border-slate-100 rounded-[3rem] group hover:bg-white hover:shadow-2xl transition-all">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm border border-slate-100 group-hover:bg-[#03235b] transition-all">
                    <svg class="w-8 h-8 text-[#03235b] group-hover:text-[#2ee0a7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 011 1v2.5a.5.5 0 01-1 0V16m-1.8-6H13a1 1 0 011 1v2a1 1 0 01-1 1h-1.8m-3-3.9a1 1 0 100 2 1 1 0 000-2zM9 18a2 2 0 100-4 2 2 0 000 4zm10 0a2 2 0 100-4 2 2 0 000 4z" />
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
        <div class="max-w-7xl mx-auto space-y-24">
            <div class="flex items-center gap-10">
                <h3 class="text-7xl font-black text-[#03235b] tracking-tighter shrink-0">LAYANAN KAMI.</h3>
                <div class="h-px bg-slate-200 w-full block"></div>
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

    <!-- Final CTA -->
    <section class="py-32 px-6 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto rounded-[4rem] bg-[#03235b] p-24 text-center relative shadow-[0_40px_80px_-20px_rgba(3,35,91,0.4)]">
             <div class="absolute inset-0 hero-pattern opacity-10"></div>
             <div class="relative z-10 space-y-12">
                <h2 class="text-6xl lg:text-7xl font-black text-white tracking-tighter leading-none uppercase italic underline decoration-[#2ee0a7] decoration-[12px] underline-offset-[16px]">DAPATKAN DOMBA<br>TERBAIK ANDA.</h2>
                <div class="flex flex-wrap justify-center gap-8 pt-12">
                    <a href="{{ route('public.catalog') }}" class="px-14 py-7 bg-white text-[#03235b] font-black text-xs uppercase tracking-[0.4em] rounded-2xl shadow-xl hover:scale-105 transition-all">Lihat Katalog</a>
                    <a href="https://wa.me/6281234567890" class="px-14 py-7 bg-transparent text-white border-2 border-white/20 font-black text-xs uppercase tracking-[0.4em] rounded-2xl hover:bg-white/5 transition-all">Pesan Lewat WA</a>
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

    <!-- Feather Icons Init -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>
