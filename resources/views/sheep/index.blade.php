<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Manajemen Domba') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Domba</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen px-10">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">
                
                <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                    <div class="p-6 lg:p-10">
                        
                        @if(session('success'))
                            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-medium text-sm">{{ session('success') }}</span>
                                </div>
                                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        @endif

                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">Data Populasi Domba</h3>
                                <p class="text-sm text-slate-500 mt-1">Kelola data dan informasi domba di peternakan.</p>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('sheep-types.index') }}" 
                                   class="group inline-flex items-center gap-2 px-5 py-3 bg-white text-slate-700 text-sm font-semibold rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 transition-all duration-300 transform hover:-translate-y-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Jenis Domba
                                </a>
                                <a href="{{ route('sheep.create') }}" 
                                   class="group inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Tambah Domba
                                </a>
                            </div>
                        </div>

                        <!-- Filter Bar -->
                        <div class="mb-8 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 relative z-[100]">
                            <form id="filter-form" method="GET" action="{{ route('sheep.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6 items-end relative z-[100]">
                                <!-- 1. Cari Kode -->
                                <div class="flex flex-col">
                                    <label for="filter_search" class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Kode Domba</label>
                                    <div class="relative group" x-data="{ value: '{{ $filters['search'] ?? '' }}' }">
                                        <input type="text" id="filter_search" name="search" x-model="value" placeholder=" Cari kode..." class="w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] pr-10 text-[13px] font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm min-h-[38px]" :class="value ? 'text-slate-600' : 'text-slate-400'">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Jenis Domba -->
                                <div class="flex flex-col">
                                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Jenis</label>
                                    @php
                                        $typeOptions = $sheepTypes->map(fn($t) => [
                                            'id' => (string) $t->id,
                                            'name' => $t->name
                                        ])->prepend(['id' => '', 'name' => 'Semua Jenis'])->values()->toArray();
                                    @endphp
                                    <x-searchable-dropdown 
                                        name="type_id"
                                        id="filter_type_id"
                                        placeholder="Semua Jenis..."
                                        :options="$typeOptions"
                                        :value="$filters['type_id'] ?? ''"
                                        :showFooter="false"
                                        :compact="true"
                                    />
                                </div>

                                <!-- 3. Status -->
                                <div class="flex flex-col">
                                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Status</label>
                                    <div x-data="{ 
                                        open: false, 
                                        selected: '{{ ($filters['status'] ?? '') === 'tersedia' ? 'Tersedia' : (($filters['status'] ?? '') === 'terjual' ? 'Terjual' : 'Semua') }}',
                                        options: [
                                            { label: 'Semua', value: '' },
                                            { label: 'Tersedia', value: 'tersedia' },
                                            { label: 'Terjual', value: 'terjual' }
                                        ],
                                        select(option) {
                                            this.selected = option.label;
                                            this.$refs.status_input.value = option.value;
                                            this.open = false;
                                        }
                                    }" class="relative">
                                        <input type="hidden" name="status" x-ref="status_input" value="{{ $filters['status'] ?? '' }}">
                                        <div @click="open = !open" 
                                            class="flex items-center justify-between w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] text-[13px] font-bold text-slate-600 cursor-pointer hover:border-blue-500 transition-all shadow-sm min-h-[38px]"
                                            :class="open ? 'border-blue-500 ring-4 ring-blue-500/10' : ''">
                                            <span x-text="selected" class="leading-none"></span>
                                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180 text-blue-500' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                        <div x-show="open" x-cloak @click.outside="open = false" 
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            class="absolute z-50 mt-1.5 w-full rounded-xl bg-white border border-slate-100 shadow-xl py-1 overflow-hidden">
                                            <template x-for="option in options" :key="option.value">
                                                <div @click="select(option)" 
                                                    class="px-3 py-2 text-[13px] font-bold cursor-pointer hover:bg-slate-50 transition-colors flex items-center justify-between"
                                                    :class="selected === option.label ? 'text-blue-600 bg-blue-50/30' : 'text-slate-600 group-hover:text-blue-600'">
                                                    <span x-text="option.label"></span>
                                                    <div x-show="selected === option.label" class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- 4. Kondisi -->
                                <div class="flex flex-col">
                                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Kondisi</label>
                                    <div x-data="{ 
                                        open: false, 
                                        selected: '{{ ($filters['condition'] ?? '') === 'sehat' ? 'Sehat' : (($filters['condition'] ?? '') === 'sakit' ? 'Sakit' : 'Semua') }}',
                                        options: [
                                            { label: 'Semua', value: '' },
                                            { label: 'Sehat', value: 'sehat' },
                                            { label: 'Sakit', value: 'sakit' }
                                        ],
                                        select(option) {
                                            this.selected = option.label;
                                            this.$refs.condition_input.value = option.value;
                                            this.open = false;
                                        }
                                    }" class="relative">
                                        <input type="hidden" name="condition" x-ref="condition_input" value="{{ $filters['condition'] ?? '' }}">
                                        <div @click="open = !open" 
                                            class="flex items-center justify-between w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] text-[13px] font-bold text-slate-600 cursor-pointer hover:border-blue-500 transition-all shadow-sm min-h-[38px]"
                                            :class="open ? 'border-blue-500 ring-4 ring-blue-500/10' : ''">
                                            <span x-text="selected" class="leading-none"></span>
                                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180 text-blue-500' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                        <div x-show="open" x-cloak @click.outside="open = false" 
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            class="absolute z-50 mt-1.5 w-full rounded-xl bg-white border border-slate-100 shadow-xl py-1 overflow-hidden">
                                            <template x-for="option in options" :key="option.value">
                                                <div @click="select(option)" 
                                                    class="px-3 py-2 text-[13px] font-bold cursor-pointer hover:bg-slate-50 transition-colors flex items-center justify-between"
                                                    :class="selected === option.label ? 'text-blue-600 bg-blue-50/30' : 'text-slate-600 group-hover:text-blue-600'">
                                                    <span x-text="option.label"></span>
                                                    <div x-show="selected === option.label" class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="xl:col-span-1"></div>

                                <!-- 5. Terapkan -->
                                <div class="flex flex-col">
                                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-black text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700 hover:shadow-blue-600/40 transform hover:-translate-y-0.5 min-h-[38px]">
                                        Terapkan
                                    </button>
                                </div>

                                <!-- 6. Reset -->
                                {{-- <div class="flex flex-col">
                                    <a href="{{ route('sheep.index') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-100 px-4 py-2 text-[13px] font-black text-slate-500 transition hover:bg-slate-200 transform hover:-translate-y-0.5 text-center min-h-[38px]">
                                        Reset
                                    </a>
                                </div> --}}
                            </form>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Foto</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kode Domba</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis (Type)</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Berat (Kg)</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Umur</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Kondisi</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Harga Taksiran</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse($sheep as $key => $s)
                                        <tr class="group hover:bg-blue-50/40 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                {{ $sheep->firstItem() + $key }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="inline-flex h-10 w-10 overflow-hidden rounded-xl bg-slate-100 border border-slate-200 shadow-sm">
                                                    @if($s->photo)
                                                        <img src="{{ asset('storage/' . $s->photo) }}" class="h-full w-full object-cover">
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center text-slate-300">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-bold text-blue-600 font-mono">
                                                {{ $s->code }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-800">
                                                {{ $s->sheepType->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-slate-600">
                                                {{ (float) $s->weight }} kg
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-slate-700">
                                                {{ $s->age }} <span class="text-[10px] text-slate-400 font-medium">bln</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $cond = strtolower($s->condition);
                                                    $condClasses = [
                                                        'sehat' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                                        'sakit ringan' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                                        'sakit parah' => 'bg-rose-50 text-rose-700 ring-rose-600/20'
                                                    ];
                                                    $cClass = $condClasses[$cond] ?? 'bg-slate-50 text-slate-700 ring-slate-600/20';
                                                @endphp
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider {{ $cClass }} ring-1 ring-inset">{{ $s->condition }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @php
                                                    $stat = strtolower($s->status);
                                                    $statClasses = [
                                                        'tersedia' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                                        'terjual' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                                        'sakit' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                                        'mati' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                                        'hilang' => 'bg-slate-50 text-slate-700 ring-slate-600/20'
                                                    ];
                                                    $sClass = $statClasses[$stat] ?? 'bg-slate-50 text-slate-700 ring-slate-600/20';
                                                @endphp
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider {{ $sClass }} ring-1 ring-inset">{{ $s->status }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-slate-800">
                                                Rp {{ number_format($s->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex justify-center items-center gap-2">
                                                    <a href="{{ route('sheep.show', $s) }}" 
                                                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                       title="Detail Domba">
                                                       <i data-feather="eye" class="h-5 w-5" aria-hidden="true"></i>
                                                    </a>
                                                    
                                                    <a href="{{ route('sheep.edit', $s) }}" 
                                                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                       title="Edit Domba">
                                                        <i data-feather="edit" class="h-5 w-5" aria-hidden="true"></i>
                                                    </a>

                                                    <form action="{{ route('sheep.destroy', $s) }}" method="POST" class="inline" data-confirm-message="Apakah Anda yakin ingin menghapus domba ini?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="group p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                                title="Hapus Data">
                                                                <i data-feather="trash-2" class="h-5 w-5" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bg-slate-50 p-4 rounded-full mb-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-slate-700">Tidak ada domba ditemukan</h3>
                                                        <p class="text-sm text-slate-500 max-w-xs mx-auto mt-1">Silakan tambahkan data domba baru.</p>
                                                        <a href="{{ route('sheep.create') }}" class="mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                                            + Tambah Domba
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6">
                            {{ $sheep->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('sheep.script')
</x-app-layout>
