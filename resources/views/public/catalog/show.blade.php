<x-public-layout>
    <x-slot name="title">Detail {{ $sheep->code }} - Domba Loka</x-slot>

    <div class="bg-[#f8fafc] min-h-screen pt-32 pb-24">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.4em] text-[#0c5197] mb-12">
                <a href="/" class="hover:opacity-70 transition-opacity">Beranda</a>
                <i data-feather="chevron-right" class="w-3 h-3 text-slate-300"></i>
                <a href="{{ route('public.catalog') }}" class="hover:opacity-70 transition-opacity">Katalog</a>
                <i data-feather="chevron-right" class="w-3 h-3 text-slate-300"></i>
                <span class="text-slate-400">{{ $sheep->code }}</span>
            </nav>

            <div class="grid lg:grid-cols-2 gap-20 items-start">
                <!-- Left: Photo Gallery -->
                <div class="space-y-8" x-data="{ 
                    activePhoto: '{{ $sheep->photo ? asset('storage/' . $sheep->photo) : asset('images/domba.png') }}',
                    photos: [
                        { id: 0, path: '{{ $sheep->photo ? asset('storage/' . $sheep->photo) : asset('images/domba.png') }}' }
                        @foreach($sheep->photos as $p)
                        , { id: {{ $loop->iteration }}, path: '{{ asset('storage/' . $p->path) }}' }
                        @endforeach
                    ]
                }">
                    <!-- Main Display -->
                    <div class="relative bg-white rounded-[4rem] overflow-hidden shadow-[0_50px_100px_-20px_rgba(0,0,0,0.1)] aspect-square group">
                        <img :src="activePhoto" :key="activePhoto" 
                             class="w-full h-full object-cover"
                             alt="Foto {{ $sheep->code }}">
                        
                        <div class="absolute top-8 left-8 flex flex-col gap-3">
                             <span class="px-5 py-2.5 bg-white/90 backdrop-blur-md text-[#03235b] text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-sm border border-white/20">
                                {{ $sheep->sheepType->name }}
                             </span>
                             <div class="bg-[#2ee0a7] px-4 py-2 rounded-xl inline-flex items-center gap-2 border border-white/20 shadow-lg self-start">
                                <div class="w-2 h-2 rounded-full bg-[#03235b] animate-pulse"></div>
                                <span class="text-[9px] font-black text-[#03235b] uppercase tracking-widest">Kondisi Prima</span>
                             </div>
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    <div class="grid grid-cols-5 gap-4">
                        <template x-for="photo in photos" :key="photo.id">
                            <button @click="activePhoto = photo.path" 
                                    class="relative aspect-square rounded-2xl overflow-hidden border-2 transition-all duration-300"
                                    :class="activePhoto === photo.path ? 'border-[#2ee0a7] shadow-lg scale-105' : 'border-transparent hover:border-slate-200'">
                                <img :src="photo.path" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Right: Information -->
                <div class="space-y-12">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <h2 class="text-xs font-black text-[#0c5197] uppercase tracking-[0.4em]">Identitas Domba</h2>
                            <h1 class="text-6xl font-black text-[#03235b] tracking-tighter uppercase italic leading-none">
                                {{ $sheep->code }}
                            </h1>
                        </div>
                        <p class="text-[11px] font-black text-slate-300 uppercase tracking-[0.3em]">ID Registrasi: #DL-{{ str_pad($sheep->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>

                    <!-- Main Status & Price -->
                    <div class="p-10 bg-white rounded-[3rem] border border-slate-100 shadow-sm space-y-8">
                        <div class="flex justify-between items-end">
                            <div class="space-y-2">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Harga Penawaran</p>
                                <p class="text-5xl font-black text-[#0c5197] tracking-tighter">Rp {{ number_format($sheep->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right space-y-2">
                                <span class="px-4 py-2 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest rounded-lg">Status: {{ ucfirst($sheep->status) }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-100 flex flex-col gap-2">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Berat Aktual</p>
                                <div class="flex items-center gap-3 text-[#03235b]">
                                    <i data-feather="target" class="w-5 h-5 text-[#2ee0a7]"></i>
                                    <span class="text-2xl font-black tracking-tight">{{ $sheep->weight }} <span class="text-sm font-bold opacity-30 uppercase tracking-widest">KG</span></span>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-100 flex flex-col gap-2">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Usia Ternak</p>
                                <div class="flex items-center gap-3 text-[#03235b]">
                                    <i data-feather="calendar" class="w-5 h-5 text-[#2ee0a7]"></i>
                                    <span class="text-2xl font-black tracking-tight">{{ $sheep->age }} <span class="text-sm font-bold opacity-30 uppercase tracking-widest">BLN</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description/About -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-black text-[#03235b] uppercase tracking-[0.4em]">Deskripsi Ternak</h3>
                        <p class="text-slate-600 font-bold leading-relaxed text-lg">
                            Domba jenis {{ $sheep->sheepType->name }} dengan kode {{ $sheep->code }} ini merupakan salah satu koleksi unggulan kami. Telah melalui audit kesehatan berkala dan memiliki riwayat pertumbuhan yang sangat baik. Memiliki struktur tubuh yang proporsional dan sangat cocok untuk kebutuhan qurban, aqiqah, maupun pembibitan lanjutan.
                        </p>
                        <ul class="grid grid-cols-2 gap-y-4 gap-x-8">
                             <li class="flex items-center gap-3 text-xs font-black text-slate-800 uppercase tracking-widest">
                                <div class="w-2 h-2 rounded-full bg-[#2ee0a7]"></div>
                                Sudah Vaksinasi
                             </li>
                             <li class="flex items-center gap-3 text-xs font-black text-slate-800 uppercase tracking-widest">
                                <div class="w-2 h-2 rounded-full bg-[#2ee0a7]"></div>
                                Pakan Organik
                             </li>
                             <li class="flex items-center gap-3 text-xs font-black text-slate-800 uppercase tracking-widest">
                                <div class="w-2 h-2 rounded-full bg-[#2ee0a7]"></div>
                                Bebas Penyakit
                             </li>
                             <li class="flex items-center gap-3 text-xs font-black text-slate-800 uppercase tracking-widest">
                                <div class="w-2 h-2 rounded-full bg-[#2ee0a7]"></div>
                                Sertifikat Sehat
                             </li>
                        </ul>
                    </div>

                    <!-- CTA Section -->
                    <div class="pt-8 flex flex-col sm:flex-row gap-6">
                        <a href="https://wa.me/6281234567890?text=Halo Domba Loka, saya tertarik untuk memesan domba dengan kode {{ $sheep->code }} ({{ $sheep->sheepType->name }}). Mohon informasi ketersediaan dan cara pemesanannya. Terima kasih!" 
                           target="_blank"
                           class="flex-1 flex items-center justify-center gap-4 py-6 bg-[#03235b] text-white text-xs font-black uppercase tracking-[0.3em] rounded-[2rem] shadow-[0_25px_50px_-15px_rgba(3,35,91,0.3)] hover:bg-[#0c5197] hover:scale-105 transition-all">
                            Pesan Sekarang
                            <i data-feather="arrow-right" class="w-5 h-5"></i>
                        </a>
                        <button onclick="window.history.back()" 
                           class="px-10 py-6 bg-white text-slate-400 border border-slate-100 font-black text-xs uppercase tracking-[0.3em] rounded-[2rem] hover:bg-slate-50 transition-all">
                            Kembali
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recommendation Section -->
            <div class="mt-40 space-y-12">
                <div class="flex justify-between items-end">
                    <div class="space-y-4">
                        <h2 class="text-xs font-black text-[#0c5197] uppercase tracking-[0.4em]">Rekomendasi Serupa</h2>
                        <h3 class="text-4xl font-black text-[#03235b] tracking-tighter uppercase italic">Mungkin Anda Suka.</h3>
                    </div>
                    <a href="{{ route('public.catalog') }}" class="text-[10px] font-black text-[#03235b] uppercase tracking-widest border-b-2 border-[#2ee0a7] pb-1">Lihat Semua Katalog</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($recommendations as $rec)
                        <a href="{{ route('public.catalog.show', $rec->code) }}" class="group bg-white rounded-[3rem] p-4 border border-slate-50 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 overflow-hidden flex flex-col">
                            <div class="relative h-48 rounded-[2rem] overflow-hidden mb-6">
                                <img src="{{ $rec->photo ? asset('storage/' . $rec->photo) : asset('images/domba.png') }}" class="w-full h-full object-cover">
                                <div class="absolute top-4 left-4">
                                     <span class="px-3 py-1 bg-white/90 backdrop-blur-md text-[#03235b] text-[8px] font-black uppercase tracking-widest rounded-lg shadow-sm">{{ $rec->sheepType->name }}</span>
                                </div>
                            </div>
                            <div class="px-2 space-y-2 mb-4">
                                <h4 class="text-lg font-black text-[#03235b] uppercase tracking-tight">{{ $rec->code }}</h4>
                                <p class="text-sm font-black text-[#0c5197]">Rp {{ number_format($rec->price, 0, ',', '.') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Feather Icons Init -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</x-public-layout>
