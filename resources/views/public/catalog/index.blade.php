<x-public-layout>
    <x-slot name="title">Katalog Premium Domba - Domba Loka</x-slot>

    <div class="bg-[#f8fafc] min-h-screen">
        <!-- Hero Header -->
        <div class="bg-white border-b border-slate-100 pt-32 pb-20 px-6">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row lg:items-end justify-between gap-12">
                <div class="space-y-6">
                    <nav class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.4em] text-[#0c5197] mb-8">
                        <a href="/" class="hover:opacity-70 transition-opacity">Beranda</a>
                        <i data-feather="chevron-right" class="w-3 h-3 text-slate-300"></i>
                        <span class="text-slate-400">Katalog</span>
                    </nav>
                    <h1 class="text-6xl lg:text-7xl font-black text-[#03235b] tracking-tighter leading-tight uppercase italic">
                        Katalog<br>
                        <span class="text-[#0c5197] underline decoration-[#2ee0a7] decoration-[8px] underline-offset-[12px]">Pilihan.</span>
                    </h1>
                    <p class="text-slate-500 font-bold max-w-xl text-lg leading-relaxed">
                        Eksplorasi koleksi domba premium kami yang telah melalui proses seleksi ketat di peternakan Cianjur.
                    </p>
                </div>
                
                <div class="flex items-center gap-8 bg-slate-50 p-8 rounded-[3rem] border border-slate-100">
                    <div class="text-right">
                        <p class="text-5xl font-black text-[#03235b] tracking-tighter leading-none">{{ $sheep->total() }}</p>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">Koleksi Aktif</p>
                    </div>
                    <div class="w-px h-16 bg-slate-200"></div>
                    <div class="flex -space-x-4">
                        @foreach($sheepTypes->take(4) as $type)
                            <div class="w-14 h-14 rounded-2xl bg-white border-4 border-slate-50 shadow-sm flex items-center justify-center font-black text-[#03235b] text-xs uppercase" title="{{ $type->name }}">
                                {{ substr($type->name, 0, 1) }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-20 space-y-20">
            <!-- Filter Bar -->
            <div class="sticky top-24 z-40">
                <div class="bg-white/80 backdrop-blur-xl p-6 rounded-[2.5rem] border border-white shadow-[0_25px_50px_-12px_rgba(0,0,0,0.05)]">
                    <form action="{{ route('public.catalog') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="relative group">
                            <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Cari Kode atau ID..." 
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-[#03235b] placeholder-slate-400 focus:ring-4 focus:ring-[#2ee0a7]/20 transition-all">
                        </div>

                        <!-- Type -->
                        <div class="relative">
                            <x-searchable-dropdown 
                                name="type_id" 
                                placeholder="Pilih Jenis"
                                :value="$filters['type_id']"
                                :options="$sheepTypes->map(fn($t) => ['id' => $t->id, 'name' => $t->name])->toArray()"
                                :showFooter="false"
                            />
                        </div>

                        <!-- Sort -->
                        <div class="relative">
                            <x-searchable-dropdown 
                                name="sort" 
                                placeholder="Urutkan"
                                :value="$filters['sort']"
                                :options="[
                                    ['id' => 'latest', 'name' => 'Stok Terbaru'],
                                    ['id' => 'price_high', 'name' => 'Harga Tertinggi'],
                                    ['id' => 'price_low', 'name' => 'Harga Terendah']
                                ]"
                                :showFooter="false"
                            />
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 bg-[#03235b] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-[#0c5197] hover:scale-105 transition-all shadow-xl active:scale-95">Terapkan</button>
                            <a href="{{ route('public.catalog') }}" class="p-4 bg-slate-100 text-slate-400 rounded-2xl hover:bg-slate-200 hover:text-slate-600 transition-all">
                                <i data-feather="refresh-cw" class="w-5 h-5"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Catalog Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-10">
                @forelse($sheep as $item)
                    <div class="group bg-white rounded-[3.5rem] p-4 border border-slate-50 shadow-[0_20px_50px_-20px_rgba(0,0,0,0.05)] hover:shadow-[0_40px_80px_-20px_rgba(3,35,91,0.15)] hover:-translate-y-3 transition-all duration-700 flex flex-col relative overflow-hidden">
                        <!-- Photo Container -->
                        <a href="{{ route('public.catalog.show', $item->code) }}" class="relative h-72 rounded-[2.5rem] overflow-hidden block">
                            @if($item->photo)
                                <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->code }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-slate-50 flex flex-col items-center justify-center gap-4">
                                    <i data-feather="image" class="w-12 h-12 text-slate-200"></i>
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Foto Sedang Disiapkan</p>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            <div class="absolute top-5 left-5 right-5 flex justify-between items-start">
                                 <span class="px-4 py-2 bg-white/90 backdrop-blur-md text-[#03235b] text-[9px] font-black uppercase tracking-widest rounded-xl shadow-sm border border-white/20">{{ $item->sheepType->name }}</span>
                                 <div class="w-8 h-8 bg-[#2ee0a7] rounded-lg flex items-center justify-center text-[#03235b] shadow-lg">
                                    <i data-feather="check" class="w-4 h-4"></i>
                                 </div>
                            </div>

                            <div class="absolute bottom-5 left-5 right-5">
                                <div class="bg-black/20 backdrop-blur-md px-4 py-2 rounded-xl inline-flex items-center gap-2 border border-white/10">
                                    <div class="w-2 h-2 rounded-full bg-[#2ee0a7] animate-pulse"></div>
                                    <span class="text-[9px] font-black text-white uppercase tracking-widest">Siap Kirim</span>
                                </div>
                            </div>
                        </a>

                        <!-- Details -->
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-8">
                            <div class="space-y-6">
                                <div class="flex justify-between items-start lg:block xl:flex xl:justify-between">
                                    <div>
                                        <a href="{{ route('public.catalog.show', $item->code) }}" class="block group/link">
                                            <h4 class="text-2xl font-black text-[#03235b] tracking-tight uppercase group-hover/link:text-[#0c5197] transition-colors">{{ $item->code }}</h4>
                                        </a>
                                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] mt-1">ID: #DL-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                    <div class="text-right lg:text-left xl:text-right mt-1 lg:mt-3 xl:mt-1">
                                        <p class="text-xl font-black text-[#0c5197] tracking-tighter">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-slate-50 p-4 rounded-2xl flex flex-col gap-1 border border-slate-100 group-hover:bg-white transition-colors duration-500">
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Berat</p>
                                        <p class="text-sm font-black text-[#03235b]">{{ $item->weight }} <span class="text-[10px] text-slate-400">KG</span></p>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl flex flex-col gap-1 border border-slate-100 group-hover:bg-white transition-colors duration-500">
                                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Usia</p>
                                        <p class="text-sm font-black text-[#03235b]">{{ $item->age }} <span class="text-[10px] text-slate-400">BLN</span></p>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('public.catalog.show', $item->code) }}" 
                               class="group/btn w-full flex items-center justify-center gap-3 py-5 bg-[#03235b] text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-[#03235b]/90 transition-all shadow-xl active:scale-95">
                                Lihat Detail
                                <i data-feather="arrow-right" class="w-4 h-4 group-hover/btn:translate-x-2 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-40 text-center space-y-8 bg-white rounded-[4rem] border border-slate-100 shadow-sm">
                        <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto border border-slate-100">
                            <i data-feather="search" class="w-10 h-10 text-slate-200"></i>
                        </div>
                        <div class="space-y-2">
                            <p class="text-2xl font-black text-[#03235b] uppercase tracking-tight">Hasil Tidak Ditemukan</p>
                            <p class="text-slate-400 font-bold">Maaf, kami tidak menemukan domba yang sesuai dengan kriteria Anda.</p>
                        </div>
                        <a href="{{ route('public.catalog') }}" class="inline-flex px-10 py-4 bg-[#03235b] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-lg">Reset Pencarian</a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($sheep->hasPages())
                <div class="pt-20 border-t border-slate-200">
                    {{ $sheep->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Feather Icons Init -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</x-public-layout>
