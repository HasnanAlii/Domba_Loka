<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'DombaLoka - Premium Livestock' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon lengkap -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icon/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icon/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/icon/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon/logo.png') }}">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2303235b' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .text-gradient {
            background: linear-gradient(to right, #03235b, #1c88da);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .yellow-glow {
            box-shadow: 0 0 20px rgba(251, 191, 36, 0.4);
        }
    </style>
</head>
<body class="antialiased bg-[#f8fafc] text-slate-900 overflow-x-hidden" x-data="{ scrolled: window.pageYOffset > 20, mobileMenu: false }" @scroll.window="scrolled = window.pageYOffset > 20">
    
    <!-- Navigasi -->
    <nav class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500 px-6 py-4"
         :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg py-3 border-b border-slate-100' : 'bg-white/50 backdrop-blur-sm py-6'">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="flex items-center gap-4 group">
                    <div class="transform transition-transform group-hover:rotate-3">
                        <x-application-logo class="w-7 h-7 text-[#03235b]" />
                    </div>
                    {{-- <span class="text-2xl font-black text-[#03235b] transition-colors tracking-tight uppercase">DombaLoka</span> --}}
                </a>
            </div>

            <div class="hidden md:flex items-center gap-10">
                <a href="{{ route('public.about') }}" class="text-xs font-black uppercase tracking-[0.2em] {{ request()->routeIs('public.about') ? 'text-[#0c5197]' : 'text-slate-600' }} hover:text-[#0c5197] transition-colors relative group">
                    Tentang Kami
                    <span class="absolute -bottom-1 left-0 {{ request()->routeIs('public.about') ? 'w-full' : 'w-0' }} h-0.5 bg-[#fbbf24] transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ route('public.catalog') }}" class="text-xs font-black uppercase tracking-[0.2em] {{ request()->routeIs('public.catalog*') ? 'text-[#0c5197]' : 'text-slate-600' }} hover:text-[#0c5197] transition-colors relative group">
                    Katalog Domba
                    <span class="absolute -bottom-1 left-0 {{ request()->routeIs('public.catalog*') ? 'w-full' : 'w-0' }} h-0.5 bg-[#fbbf24] transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ url('/') }}#services" class="text-xs font-black uppercase tracking-[0.2em] text-slate-600 hover:text-[#0c5197] transition-colors relative group">
                    Layanan Kami
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#fbbf24] transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ url('/') }}#faq" class="text-xs font-black uppercase tracking-[0.2em] text-slate-600 hover:text-[#0c5197] transition-colors relative group">
                    FAQ
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-[#fbbf24] transition-all group-hover:w-full"></span>
                </a>
            </div>

            <div class="flex items-center gap-5">
                {{-- @if (Route::has('login'))
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
                               class="hidden sm:block px-6 py-2.5 bg-[#fbbf24] text-[#03235b] font-black text-[10px] uppercase tracking-[0.2em] rounded-xl shadow-xl hover:scale-105 transition-all">
                                Bermitra
                            </a>
                        @endif
                    @endauth
                @endif --}}
                
                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden w-10 h-10 flex flex-col items-center justify-center gap-1.5 focus:outline-none">
                    <span class="w-6 h-0.5 bg-[#03235b] transition-all transform" :class="mobileMenu ? 'rotate-45 translate-y-2' : ''"></span>
                    <span class="w-6 h-0.5 bg-[#03235b] transition-all" :class="mobileMenu ? 'opacity-0' : ''"></span>
                    <span class="w-6 h-0.5 bg-[#03235b] transition-all transform" :class="mobileMenu ? '-rotate-45 -translate-y-2' : ''"></span>
                </button>
            </div>
        </div>
    </nav>

        <!-- Mobile Menu Backdrop -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[80] bg-slate-900/40 backdrop-blur-sm md:hidden"
             @click="mobileMenu = false"
             x-cloak></div>

        <!-- Mobile Menu Sidebar -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed top-0 right-0 bottom-0 w-[80%] max-w-sm z-[90] bg-white pt-28 px-6 md:hidden shadow-2xl border-l border-slate-100 overflow-y-auto" 
             x-cloak>
            <div class="space-y-8 px-2">
                <a @click="mobileMenu = false" href="{{ route('public.about') }}" class="block text-2xl font-black text-[#03235b] tracking-tighter uppercase">Tentang Kami</a>
                <a @click="mobileMenu = false" href="{{ route('public.catalog') }}" class="block text-2xl font-black text-[#03235b] tracking-tighter uppercase">Katalog</a>
                <a @click="mobileMenu = false" href="{{ url('/') }}#services" class="block text-2xl font-black text-[#03235b] tracking-tighter uppercase">Layanan</a>
                <a @click="mobileMenu = false" href="{{ url('/') }}#faq" class="block text-2xl font-black text-[#03235b] tracking-tighter uppercase">FAQ</a>
            </div>
        </div>
    <main class="min-h-screen pt-24 pb-20 overflow-x-hidden">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white pt-32 pb-12 px-6 border-t border-slate-100">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-24">
                <!-- Brand Section -->
                <div class="space-y-8">
                     <div class="flex items-center gap-4">
                        <x-application-logo class="w-10 h-10 text-[#03235b]" />
                        <span class="text-3xl font-black text-[#03235b] tracking-tighter uppercase">DombaLoka</span>
                    </div>
                    <p class="text-slate-400 font-bold leading-relaxed italic pr-4">
                        "Pusat domba premium Jawa Barat. Fokus pada kualitas, kesehatan, dan kepuasan pelanggan di setiap transaksi."
                    </p>
                    {{-- <div class="flex items-center gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-[#03235b] hover:bg-[#03235b] hover:text-white transition-all">
                            <i data-feather="instagram" class="w-4 h-4"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-[#03235b] hover:bg-[#03235b] hover:text-white transition-all">
                            <i data-feather="facebook" class="w-4 h-4"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-[#03235b] hover:bg-[#03235b] hover:text-white transition-all">
                            <i data-feather="youtube" class="w-4 h-4"></i>
                        </a>
                    </div> --}}
                </div>

                <!-- Navigation Section -->
                <div class="space-y-8">
                    <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-[0.5em]">Navigasi</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('public.about') }}" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('public.catalog') }}" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors">Katalog Domba</a></li>
                        <li><a href="#services" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors">Layanan Kami</a></li>
                        <li><a href="#testimonials" class="text-xs font-black text-slate-900 uppercase tracking-widest hover:text-[#0c5197] transition-colors">Testimoni</a></li>
                    </ul>
                </div>

                <!-- Services Section -->
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
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#fbbf24] shrink-0">
                                <i data-feather="map-pin" class="w-4 h-4"></i>
                            </div>
                            <p class="text-[11px] font-bold text-slate-600 leading-relaxed uppercase tracking-wider mt-1">
                                Desa Galudra, Kec Cugenang, Kab Cianjur, Jawa Barat
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#fbbf24] shrink-0">
                                <i data-feather="phone" class="w-4 h-4"></i>
                            </div>
                            <p class="text-[11px] font-black text-[#03235b] uppercase tracking-widest">
                                +62 877 0846 3586
                            </p>
                        </div>
                        {{-- <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-[#fbbf24] shrink-0">
                                <i data-feather="mail" class="w-4 h-4"></i>
                            </div>
                            <p class="text-[11px] font-black text-[#03235b] uppercase tracking-widest">
                                hello@dombaloka.com
                            </p>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="pt-12 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6 text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">
                 <p>&copy; {{ date('Y') }} DombaLoka Farm • Cianjur, Jawa Barat</p>
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
                    <div class="w-10 h-10 bg-[#fbbf24] rounded-xl flex items-center justify-center text-[#03235b]">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="M9,15a1,1,0,1,0,1,1A1,1,0,0,0,9,15ZM2,14a1,1,0,0,0-1,1v2a1,1,0,0,0,2,0V15A1,1,0,0,0,2,14Zm20,0a1,1,0,0,0-1,1v2a1,1,0,0,0,2,0V15A1,1,0,0,0,22,14ZM17,7H13V5.72A2,2,0,0,0,14,4a2,2,0,0,0-4,0,2,2,0,0,0,1,1.72V7H7a3,3,0,0,0-3,3v9a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V10A3,3,0,0,0,17,7ZM13.72,9l-.5,2H10.78l-.5-2ZM18,19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V10A1,1,0,0,1,7,9H8.22L9,12.24A1,1,0,0,0,10,13h4a1,1,0,0,0,1-.76L15.78,9H17a1,1,0,0,1,1,1Zm-3-4a1,1,0,1,0,1,1A1,1,0,0,0,15,15Z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black uppercase tracking-widest">LokaAI</h4>
                        <p class="text-[9px] font-bold text-[#fbbf24] uppercase tracking-[0.2em]">Asisten Virtual</p>
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
                           class="flex-1 px-5 py-3 bg-slate-50 border-none rounded-2xl text-sm font-bold placeholder-slate-400 focus:ring-2 focus:ring-[#fbbf24] transition-all">
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
    
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>

</body>
</html>
