<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Update Pertumbuhan') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Pertumbuhan Domba</span>
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

                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">Catatan Pertumbuhan</h3>
                                <p class="text-sm text-slate-500 mt-1">Kelola data pemantauan berat domba secara berkala.</p>
                            </div>
                            
                            <a href="{{ route('growths.create') }}" 
                               class="group inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Tambah Data
                            </a>
                        </div>

                        <div class="relative z-[200] mb-6 overflow-visible rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                            <form id="filter-form" action="{{ route('growths.index') }}" method="GET"
                                class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6 items-end">
                                
                                <!-- 1. Rentang Tanggal -->
                                <div class="flex flex-col">
                                    <label for="filter_date_range"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Rentang Tanggal</label>
                                    <div class="relative group">
                                        <input type="text" id="filter_date_range" name="date_range" value="{{ $filters['date_range'] ?? '' }}"
                                            placeholder="Pilih rentang..."
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] pr-10 text-[13px] font-bold text-slate-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm min-h-[38px] cursor-pointer">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Domba -->
                                <div class="flex flex-col">
                                    <label for="filter_sheep_id" class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Pilih Domba</label>
                                    @php
                                        $sheepFormatted = $sheepOptions->map(fn($s) => [
                                            'id' => $s->id,
                                            'name' => $s->code . ' (' . ($s->sheepType->name ?? '-') . ')'
                                        ])->prepend(['id' => '', 'name' => 'Semua Domba'])->values()->toArray();
                                    @endphp
                                    <x-searchable-dropdown 
                                        name="sheep_id" 
                                        id="filter_sheep_id" 
                                        placeholder="Semua Domba..."
                                        :options="$sheepFormatted"
                                        :value="$filters['sheep_id']"
                                        :showFooter="false"
                                        :compact="true"
                                    />
                                </div>

                                <!-- 3. Status Pencapaian -->
                                <div class="flex flex-col">
                                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Status Target</label>
                                    <div x-data="{ 
                                        open: false, 
                                        selected: '{{ ($filters['status'] ?? '') === 'reached' ? 'Tercapai' : (($filters['status'] ?? '') === 'not_reached' ? 'Belum Tercapai' : 'Semua') }}',
                                        options: [
                                            { label: 'Semua', value: '' },
                                            { label: 'Tercapai', value: 'reached' },
                                            { label: 'Belum Tercapai', value: 'not_reached' }
                                        ],
                                        select(option) {
                                            this.selected = option.label;
                                            this.$refs.status_input.value = option.value;
                                            this.open = false;
                                        }
                                    }" class="relative">
                                        <input type="hidden" name="status" x-ref="status_input" value="{{ $filters['status'] ?? '' }}">
                                        <div @click="open = !open" 
                                            class="flex items-center justify-between w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] text-[13px] font-bold cursor-pointer hover:border-blue-500 transition-all shadow-sm min-h-[38px]"
                                            :class="open ? 'border-blue-500 ring-4 ring-blue-500/10' : ''">
                                            <span x-text="selected" class="leading-none" :class="selected === 'Semua' ? 'text-slate-400' : 'text-slate-600'"></span>
                                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180 text-blue-500' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                        <div x-show="open" x-cloak @click.outside="open = false" 
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            class="absolute z-[200] mt-2 w-full rounded-xl bg-white border border-slate-100 shadow-2xl py-1">
                                            <template x-for="option in options" :key="option.value">
                                                <div @click="select(option)" 
                                                    class="px-3 py-2 text-[13px] font-bold cursor-pointer hover:bg-slate-50 transition-colors flex items-center justify-between"
                                                    :class="selected === option.label ? 'text-blue-600 bg-blue-50/30' : 'text-slate-600'">
                                                    <span x-text="option.label"></span>
                                                    <div x-show="selected === option.label" class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="xl:col-span-2"></div>

                                <!-- Tombol Terapkan -->
                                <div class="flex flex-col">
                                    <button type="submit" 
                                        class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-black text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700 hover:shadow-blue-600/40 transform hover:-translate-y-0.5 min-h-[38px]">
                                        Terapkan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kode Domba</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Berat Aktual (Kg)</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Target (Kg)</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse($growths as $key => $growth)
                                        <tr class="group hover:bg-blue-50/40 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                {{ $growths->firstItem() + $key }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-800">
                                                {{ \Carbon\Carbon::parse($growth->date)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-bold text-blue-600 font-mono tracking-wider">
                                                {{ $growth->sheep->code ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $growth->weight >= $growth->target ? 'text-emerald-600' : 'text-red-600' }}">
                                                {{ (float) $growth->weight }} kg
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-slate-500">
                                                {{ (float) $growth->target }} kg
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($growth->weight >= $growth->target)
                                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Tercapai</span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">Belum Tercapai</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex justify-center items-center gap-2">
                                                    <a href="{{ route('growths.show', $growth) }}" 
                                                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                       title="Detail Pertumbuhan">
                                                       <i data-feather="eye" class="h-5 w-5" aria-hidden="true"></i>
                                                    </a>
                                                    
                                                    <a href="{{ route('growths.edit', $growth) }}" 
                                                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                       title="Edit Data">
                                                        <i data-feather="edit" class="h-5 w-5" aria-hidden="true"></i>
                                                    </a>

                                                    <form action="{{ route('growths.destroy', $growth) }}" method="POST" class="inline" data-confirm-message="Apakah Anda yakin ingin menghapus data ini?">
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
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-slate-700">Tidak ada catatan ditemukan</h3>
                                                        <p class="text-sm text-slate-500 max-w-xs mx-auto mt-1">Silakan tambahkan data pertumbuhan domba.</p>
                                                        <a href="{{ route('growths.create') }}" class="mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                                            + Tambah Data
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
                            {{ $growths->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Custom Styling for Flatpickr with Sidebar */
        .flatpickr-calendar {
            display: flex !important;
            flex-direction: row;
            border-radius: 24px !important;
            box-shadow: 0 50px 100px -20px rgba(15, 23, 42, 0.2) !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            overflow: hidden;
            background: #fff !important;
            width: auto !important;
        }

        /* Sidebar Presets Styling */
        .flatpickr-sidebar {
            width: 180px;
            background: #f8fbff;
            border-right: 1px solid #f1f5f9;
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .preset-btn {
            width: 100%;
            text-align: left;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            border-radius: 12px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .preset-btn:hover {
            background: #fff;
            color: #2563eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .preset-btn.active {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.25);
        }

        /* Adjust Calendar Positioning */
        .flatpickr-months { background: white !important; }
        .flatpickr-innerContainer { padding: 10px; }
        .flatpickr-days { width: auto !important; }

        /* Custom Hover/Selected Days */
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #fff !important;
            border-radius: 12px !important;
        }
        .flatpickr-day.inRange {
            background: #eff6ff !important;
            box-shadow: none !important;
            color: #1e40af !important;
        }
    </style>

    <!-- Flatpickr Assets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    @include('growths.script')
</x-app-layout>
