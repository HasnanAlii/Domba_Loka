<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang Kami - Domba Loka</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            scroll-behavior: smooth;
        }

        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2303235b' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        [x-cloak] {
            display: none !important;
        }

        .text-gradient {
            background: linear-gradient(to right, #03235b, #1c88da);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="antialiased bg-[#f8fafc] text-slate-900 overflow-x-hidden" x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.pageYOffset > 20">

    <!-- Navigasi -->
    <nav class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500 px-6 py-4"
        :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg py-3 border-b border-slate-100' :
            'bg-white/50 backdrop-blur-sm py-6'">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="flex items-center gap-4 group">
                    <div
                        class="bg-white p-2 rounded-xl shadow-md border border-slate-100 transform transition-transform group-hover:rotate-3">
                        <x-application-logo class="w-7 h-7 text-[#03235b]" />
                    </div>
                    <span class="text-2xl font-black text-[#03235b] tracking-tight uppercase">Domba Loka</span>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-10">
                <a href="{{ url('/') }}"
                    class="text-xs font-black uppercase tracking-[0.2em] text-slate-600 hover:text-[#0c5197] transition-colors relative group">
                    Beranda
                </a>
                <a href="{{ route('public.catalog') }}"
                    class="text-xs font-black uppercase tracking-[0.2em] text-slate-600 hover:text-[#0c5197] transition-colors relative group">
                    Katalog Domba
                </a>
                <a href="{{ route('public.about') }}"
                    class="text-xs font-black uppercase tracking-[0.2em] text-[#0c5197] transition-colors relative group">
                    Tentang Kami
                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-[#2ee0a7]"></span>
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
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Content -->
    <main class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-24 items-center mb-32">
                <div class="space-y-12">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-1 bg-[#2ee0a7]"></div>
                            <h2 class="text-xs font-black text-[#0c5197] uppercase tracking-[0.4em]">Cerita Kami</h2>
                        </div>
                        <h1
                            class="text-7xl lg:text-8xl font-black text-[#03235b] tracking-tighter leading-[0.9] uppercase italic">
                            Domba<br>
                            <span
                                class="text-gradient underline decoration-[#2ee0a7] decoration-[12px] underline-offset-[16px]">Loka.</span>
                        </h1>
                    </div>

                    <div class="space-y-8 text-slate-600 font-bold leading-relaxed text-lg">
                        <p>
                            Domba Loka lahir dari sebuah visi sederhana: menyediakan akses bagi masyarakat untuk
                            mendapatkan domba dengan kualitas terbaik dan kesehatan yang terjamin langsung dari jantung
                            peternakan Jawa Barat.
                        </p>
                        <p>
                            Berpusat di Cianjur, kami mendedikasikan diri untuk melestarikan keunggulan domba lokal sambil
                            menerapkan standar profesionalisme tinggi dalam setiap proses transaksi, mulai dari
                            pemilihan bibit hingga pengiriman ke tangan konsumen.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-6 pt-8">
                        <div
                            class="p-8 bg-white rounded-[2.5rem] shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-center text-center">
                            <p class="text-xs font-black text-emerald-500 uppercase tracking-widest mb-2">Kualitas</p>
                            <p class="text-4xl lg:text-5xl font-black text-[#03235b] tracking-tighter">100%</p>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-3">Kesehatan
                                Terjamin</p>
                        </div>
                        <div
                            class="p-8 bg-[#03235b] rounded-[2.5rem] shadow-2xl text-white flex flex-col justify-center text-center">
                            <p class="text-xs font-black text-[#2ee0a7] uppercase tracking-widest mb-2">Standar</p>
                            <p class="text-2xl lg:text-3xl font-black tracking-tight leading-none">PROFESIONAL</p>
                            <p class="text-[9px] font-black text-blue-100/40 uppercase tracking-[0.2em] mt-4">Manajemen
                                Ternak</p>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -inset-20 bg-[#2ee0a7]/5 rounded-full blur-[100px] animate-pulse"></div>
                    
                    <!-- Image Container -->
                    <div class="relative bg-white rounded-[5rem] p-8 lg:p-16 shadow-[0_50px_100px_-20px_rgba(3,35,91,0.1)] border border-slate-50 flex items-center justify-center overflow-hidden group">
                        <!-- Background Decorative Circles -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-[#03235b]/5 rounded-full -mr-32 -mt-32 transition-transform group-hover:scale-110 duration-700"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-[#2ee0a7]/5 rounded-full -ml-24 -mb-24 transition-transform group-hover:scale-110 duration-700"></div>

                        <!-- Real Sheep PNG Image -->
                        <img src="/images/domba.png" alt="Domba Loka Premium" class="relative z-10 w-full h-auto max-w-[450px] drop-shadow-2xl transform transition-transform group-hover:scale-110 duration-700">

                        <!-- Quote Overlay -->
                        <div class="absolute bottom-10 left-10 right-10 z-20">
                            <div class="bg-white/10 backdrop-blur-md p-6 rounded-[2rem] border border-white/20 text-center shadow-xl">
                                <p class="text-[#03235b] text-sm font-black italic leading-relaxed">
                                    "Pusat domba kualitas warisan yang kami jaga setiap harinya."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filosofi & Nilai Inti -->
            <div class="py-32 border-t border-slate-100">
                <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                    <h2 class="text-xs font-black text-[#0c5197] uppercase tracking-[0.4em]">Filosofi Kami</h2>
                    <h3 class="text-5xl font-black text-[#03235b] tracking-tighter">Pilar Utama Domba Loka.</h3>
                    <p class="text-slate-500 font-bold italic">"Kualitas tidak pernah menjadi kebetulan; itu selalu
                        merupakan hasil dari niat yang lurus, usaha keras, dan pelaksanaan yang cerdas."</p>
                </div>

                <div class="grid md:grid-cols-4 gap-6">
                    <div
                        class="p-10 bg-white rounded-[3rem] border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                        <div
                            class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-6">
                            <i data-feather="check-circle"></i>
                        </div>
                        <h4 class="text-lg font-black text-[#03235b] uppercase tracking-wider mb-2">Integritas</h4>
                        <p class="text-sm font-bold text-slate-400">Kejujuran dalam timbangan dan transparansi riwayat
                            ternak adalah janji kami.</p>
                    </div>
                    <div
                        class="p-10 bg-white rounded-[3rem] border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                        <div
                            class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 mb-6">
                            <i data-feather="award"></i>
                        </div>
                        <h4 class="text-lg font-black text-[#03235b] uppercase tracking-wider mb-2">Kualitas</h4>
                        <p class="text-sm font-bold text-slate-400">Hanya domba dengan standar kesehatan A+ yang masuk
                            ke dalam katalog kami.</p>
                    </div>
                    <div
                        class="p-10 bg-white rounded-[3rem] border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                        <div
                            class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 mb-6">
                            <i data-feather="users"></i>
                        </div>
                        <h4 class="text-lg font-black text-[#03235b] uppercase tracking-wider mb-2">Kemitraan</h4>
                        <p class="text-sm font-bold text-slate-400">Membangun ekosistem yang saling menguntungkan dengan
                            peternak lokal Cianjur.</p>
                    </div>
                    <div
                        class="p-10 bg-white rounded-[3rem] border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                        <div
                            class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 mb-6">
                            <i data-feather="heart"></i>
                        </div>
                        <h4 class="text-lg font-black text-[#03235b] uppercase tracking-wider mb-2">Kepuasan</h4>
                        <p class="text-sm font-bold text-slate-400">Fokus utama kami adalah menghadirkan senyum puas di
                            wajah setiap pelanggan.</p>
                    </div>
                </div>
            </div>

            <!-- Proses Kami Section -->
            <div class="py-32 bg-[#03235b] rounded-[5rem] px-12 lg:px-24 mb-32 relative overflow-hidden">
                <div class="absolute inset-0 hero-pattern opacity-10"></div>
                <div class="relative z-10 grid lg:grid-cols-2 gap-20 items-center">
                    <div class="space-y-10">
                        <div class="space-y-4">
                            <h2 class="text-xs font-black text-[#2ee0a7] uppercase tracking-[0.4em]">Standar
                                Operasional</h2>
                            <h3 class="text-5xl font-black text-white tracking-tighter leading-tight">BAGAIMANA
                                KAMI<br>MENJAGA KUALITAS.</h3>
                        </div>
                        <div class="space-y-6">
                            <div class="flex gap-6 items-start">
                                <div
                                    class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white font-black shrink-0">
                                    1</div>
                                <div>
                                    <h4 class="text-white font-black uppercase text-sm tracking-widest mb-2">Seleksi
                                        Bibit Unggul</h4>
                                    <p class="text-blue-100/60 text-sm font-bold italic">Kami memilih keturunan domba
                                        terbaik dengan genetika kuat dari breeder terpercaya.</p>
                                </div>
                            </div>
                            <div class="flex gap-6 items-start">
                                <div
                                    class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white font-black shrink-0">
                                    2</div>
                                <div>
                                    <h4 class="text-white font-black uppercase text-sm tracking-widest mb-2">Pakan
                                        Formulasi Khusus</h4>
                                    <p class="text-blue-100/60 text-sm font-bold italic">Nutrisi seimbang antara serat
                                        dan protein untuk memastikan pertumbuhan bobot yang optimal.</p>
                                </div>
                            </div>
                            <div class="flex gap-6 items-start">
                                <div
                                    class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white font-black shrink-0">
                                    3</div>
                                <div>
                                    <h4 class="text-white font-black uppercase text-sm tracking-widest mb-2">Audit
                                        Kesehatan Rutin</h4>
                                    <p class="text-blue-100/60 text-sm font-bold italic">Pemeriksaan fisik dan
                                        pemberian vitamin berkala untuk mencegah timbulnya penyakit.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute -inset-4 bg-white/5 rounded-[4rem] group-hover:bg-white/10 transition-all">
                        </div>
                        <div class="relative rounded-[3.5rem] overflow-hidden shadow-2xl">
                            <img src="/images/hero.png" alt="Proses Domba Loka"
                                class="w-full h-[500px] object-cover">
                            <!-- Overlay Shadow -->
                            <div class="absolute inset-0 bg-[#03235b]/40 group-hover:bg-[#03235b]/20 transition-all duration-500"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jangkauan Section -->
            <div class="mb-32">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="order-2 lg:order-1">
                        <h2 class="text-8xl font-black text-slate-100 tracking-tighter mb-[-30px] select-none">
                            COVERAGE.</h2>
                        <div class="relative z-10 space-y-8">
                            <h3 class="text-4xl font-black text-[#03235b] tracking-tight">Siap Kirim ke Seluruh Pelosok
                                Jawa Barat.</h3>
                            <p class="text-lg text-slate-600 font-bold leading-relaxed">
                                Dari markas kami di Cianjur, Domba Loka telah membangun jaringan logistik mandiri yang
                                mampu melayani pengiriman ke Bandung, Jakarta, Bogor, Depok, Tangerang, Bekasi, hingga
                                Cirebon.
                            </p>
                            <div class="grid grid-cols-2 gap-4">
                                <ul class="space-y-3">
                                    <li class="flex items-center gap-3 text-sm font-black text-slate-800"><i
                                            data-feather="map-pin" class="w-4 h-4 text-[#2ee0a7]"></i> Bandung Raya
                                    </li>
                                    <li class="flex items-center gap-3 text-sm font-black text-slate-800"><i
                                            data-feather="map-pin" class="w-4 h-4 text-[#2ee0a7]"></i> Jabodetabek
                                    </li>
                                    <li class="flex items-center gap-3 text-sm font-black text-slate-800"><i
                                            data-feather="map-pin" class="w-4 h-4 text-[#2ee0a7]"></i> Priangan Timur
                                    </li>
                                </ul>
                                <ul class="space-y-3">
                                    <li class="flex items-center gap-3 text-sm font-black text-slate-800"><i
                                            data-feather="map-pin" class="w-4 h-4 text-[#2ee0a7]"></i> Purwakarta &
                                        Subang</li>
                                    <li class="flex items-center gap-3 text-sm font-black text-slate-800"><i
                                            data-feather="map-pin" class="w-4 h-4 text-[#2ee0a7]"></i> Sukabumi &
                                        Cianjur</li>
                                    <li class="flex items-center gap-3 text-sm font-black text-slate-800"><i
                                            data-feather="map-pin" class="w-4 h-4 text-[#2ee0a7]"></i> Cirebon &
                                        Sekitarnya</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div
                        class="order-1 lg:order-2 bg-slate-100 rounded-[4rem] h-[450px] flex items-center justify-center p-12 border border-slate-200 overflow-hidden group">
                        <div class="text-center space-y-6 transition-transform group-hover:scale-105 duration-700">
                            <img src="/images/kirim.png" alt="Pengiriman Domba Loka" class="w-full h-auto max-w-[420px] mx-auto drop-shadow-2xl">
                            <p class="text-slate-400 font-black uppercase tracking-widest text-xs">Jaminan Pengiriman
                                Aman & Tepat Waktu</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Final -->
            <div
                class="bg-white rounded-[4rem] p-20 text-center border border-slate-100 shadow-xl mb-32 relative overflow-hidden group">
                <div
                    class="absolute inset-0 bg-slate-50 translate-y-full group-hover:translate-y-0 transition-transform duration-700">
                </div>
                <div class="relative z-10 space-y-10">
                    <h3 class="text-5xl font-black text-[#03235b] tracking-tighter leading-none">SIAP MEMULAI
                        KERJASAMA?</h3>
                    <p class="text-slate-500 font-bold max-w-xl mx-auto">Jelajahi katalog domba pilihan kami atau
                        konsultasikan kebutuhan kurban, aqiqah, dan suplai restoran Anda bersama kami.</p>
                    <div class="flex flex-wrap justify-center gap-6">
                        <a href="{{ route('public.catalog') }}"
                            class="px-12 py-5 bg-[#03235b] text-white font-black text-xs uppercase tracking-[0.3em] rounded-2xl shadow-xl hover:scale-105 transition-all">Pilih
                            Domba</a>
                        <a href="https://wa.me/6281234567890"
                            class="px-12 py-5 bg-white text-[#03235b] border-2 border-slate-100 font-black text-xs uppercase tracking-[0.3em] rounded-2xl hover:bg-slate-50 transition-all">Hubungi
                            Admin</a>
                    </div>
                </div>
            </div>

            <!-- Scripts and Icons -->
            <script src="https://unpkg.com/feather-icons"></script>
            <script>
                feather.replace();
            </script>
        </div>
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
