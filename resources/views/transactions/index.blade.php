<x-app-layout>
    @php
        $isPenjualan = $selectedType === 'penjualan';
        $isPembelian = $selectedType === 'pembelian';
        $pageTitle = $isPenjualan
            ? 'Manajemen Penjualan'
            : ($isPembelian
                ? 'Manajemen Pembelian'
                : 'Manajemen Transaksi');
        $sectionTitle = $isPenjualan ? 'Daftar Penjualan' : ($isPembelian ? 'Daftar Pembelian' : 'Daftar Transaksi');
        $sectionDescription = $isPenjualan
            ? 'Kelola transaksi penjualan ke customer.'
            : ($isPembelian
                ? 'Kelola transaksi pembelian dari supplier.'
                : 'Kelola data jual beli dan rekap transaksi.');
        $createLabel = $isPenjualan ? 'Tambah Penjualan' : ($isPembelian ? 'Tambah Pembelian' : 'Tambah Transaksi');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __($pageTitle) }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Transaksi</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12  bg-[#f0f6ff] min-h-screen px-10">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white shadow-xl shadow-slate-200/60 rounded-2xl border border-slate-100">
                    <div class="p-6 lg:p-10">


                        <div class="relative z-[250] flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">{{ $sectionTitle }}</h3>
                                <p class="text-sm text-slate-500 mt-1">{{ $sectionDescription }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="relative" x-data="{ open: false }" :class="open ? 'z-[100]' : 'z-10'">
                                    <button @click="open = !open" @click.outside="open = false" type="button"
                                        class="group inline-flex items-center gap-2 px-5 py-3 bg-amber-400 text-black text-sm font-bold rounded-2xl shadow-lg shadow-amber-400/30 hover:bg-amber-500 hover:shadow-amber-500/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                        {{-- <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                        </svg> --}}
                                        Export Laporan
                                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div x-show="open" x-cloak
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave="transition ease-in duration-100"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute right-0 top-[calc(100%+8px)] z-[100] w-36 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden origin-top-right">
                                        <!-- Excel -->
                                        <a href="{{ route('transactions.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'excel'])) }}"
                                            class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors group/item">
                                            Export Excel
                                        </a>
                                        <div class="border-t border-slate-50"></div>
                                        <!-- PDF -->
                                        <a href="{{ route('transactions.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'pdf'])) }}"
                                            class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition-colors group/item">
                                            Export PDF
                                        </a>
                                    </div>
                                </div>

                                <a href="{{ route('transactions.create', $selectedType ? ['type' => $selectedType] : []) }}"
                                    class="group inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $createLabel }}
                                </a>
                            </div>
                        </div>

                        <div class="mb-6 rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                            <form id="filter-form" action="{{ route('transactions.index') }}" method="GET"
                                class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
                                @if ($selectedType)
                                    <input type="hidden" name="type" value="{{ $selectedType }}">
                                @endif

                                <div>
                                    <label for="filter_date_range"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Rentang
                                        Tanggal</label>
                                    <div class="relative group">
                                        <input type="text" id="filter_date_range" name="date_range"
                                            value="{{ request('date_range') }}" placeholder="Pilih rentang..."
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] pr-10 text-[13px] font-bold text-slate-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm min-h-[38px] cursor-pointer">
                                        <div
                                            class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col">
                                    <label for="filter_reference_number"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">No
                                        Ref</label>
                                    <div class="relative group" x-data="{
                                        value: '{{ $filters['reference_number'] }}',
                                        init() {
                                            if (new URLSearchParams(window.location.search).get('reference_number')) {
                                                this.$nextTick(() => {
                                                    const input = this.$refs.refInput;
                                                    input.focus();
                                                    input.setSelectionRange(input.value.length, input.value.length);
                                                });
                                            }
                                        }
                                    }">
                                        <input type="text" id="filter_reference_number" name="reference_number"
                                            x-ref="refInput" x-model="value"
                                            @input.debounce.750ms="$el.closest('form').submit()" placeholder="Cari..."
                                            class="w-full rounded-xl border border-slate-200 bg-white pl-4 pr-10 py-[7px] text-[13px] font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm min-h-[38px] transition-all duration-300"
                                            :class="value ? 'text-slate-800' : 'text-slate-400'">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-2">
                                            <button x-show="value" type="button"
                                                @click="value = ''; $nextTick(() => $el.closest('form').submit())"
                                                class="text-slate-300 hover:text-rose-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                            <svg class="w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative group">
                                    <label for="filter_sheep_id"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Domba</label>
                                    @php
                                        $sheepFormatted = $sheepOptions
                                            ->map(
                                                fn($s) => [
                                                    'id' => $s->id,
                                                    'name' => $s->code . ' (' . ($s->sheepType->name ?? '-') . ')',
                                                ],
                                            )
                                            ->prepend(['id' => '', 'name' => 'Semua Domba'])
                                            ->values()
                                            ->toArray();
                                    @endphp
                                    <x-searchable-dropdown name="sheep_id" id="filter_sheep_id"
                                        placeholder="Semua..." :options="$sheepFormatted" :value="$filters['sheep_id']" :showFooter="false"
                                        :compact="true" />
                                </div>

                                <div class="flex flex-col">
                                    <label for="filter_total_price"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Harga</label>
                                    <div x-data="{ value: '{{ $filters['total_price'] }}' }">
                                        <input type="number" id="filter_total_price" name="total_price"
                                            min="0" step="0.01" x-model="value" placeholder="0"
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] text-[13px] font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm min-h-[38px]"
                                            :class="value && value != 0 ? 'text-slate-600' : 'text-slate-400'">
                                    </div>
                                </div>

                                @if ($isPenjualan)
                                    <div class="relative group">
                                        <label for="filter_customer_id"
                                            class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Pelanggan</label>
                                        @php
                                            $customerFormatted = $customerOptions
                                                ->map(
                                                    fn($c) => [
                                                        'id' => $c->id,
                                                        'name' => $c->name,
                                                    ],
                                                )
                                                ->prepend(['id' => '', 'name' => 'Semua Pelanggan'])
                                                ->values()
                                                ->toArray();
                                        @endphp
                                        <x-searchable-dropdown name="customer_id" id="filter_customer_id"
                                            placeholder="Pelanggan..." :options="$customerFormatted" :value="$filters['customer_id']"
                                            :showFooter="false" :compact="true" />
                                    </div>
                                @elseif ($isPembelian)
                                    <div class="relative group">
                                        <label for="filter_supplier_id"
                                            class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Supplier</label>
                                        @php
                                            $supplierFormatted = $supplierOptions
                                                ->map(
                                                    fn($s) => [
                                                        'id' => $s->id,
                                                        'name' => $s->name,
                                                    ],
                                                )
                                                ->prepend(['id' => '', 'name' => 'Semua Supplier'])
                                                ->values()
                                                ->toArray();
                                        @endphp
                                        <x-searchable-dropdown name="supplier_id" id="filter_supplier_id"
                                            placeholder="Supplier..." :options="$supplierFormatted" :value="$filters['supplier_id']"
                                            :showFooter="false" :compact="true" />
                                    </div>
                                @endif

                                <div class="flex flex-col justify-end pb-0.5">
                                    <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-black text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700 hover:shadow-blue-600/40 transform hover:-translate-y-0.5">Terapkan</button>
                                </div>
                            </form>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white">
                            <div class="overflow-visible">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                No</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                No. Ref</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Jenis</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Pihak (C/S)</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Domba</th>
                                            <th
                                                class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Total Harga</th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse($transactions as $key => $t)
                                            <tr class="group hover:bg-blue-50/40 transition-colors duration-200">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                    {{ $transactions->firstItem() + $key }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-600">
                                                    {{ $t->created_at->format('d/m/Y') }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-bold text-blue-600 font-mono tracking-wider">
                                                    {{ $t->reference_number }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-800">
                                                    @if ($t->type === 'penjualan')
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Penjualan</span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/20">Pembelian</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-800">
                                                    @if ($t->customer_id)
                                                        <span
                                                            class="text-emerald-700 bg-emerald-50 px-2 py-1 rounded-md text-xs mr-1">C</span>
                                                        {{ $t->customer->name ?? '-' }}
                                                    @elseif($t->supplier_id)
                                                        <span
                                                            class="text-rose-700 bg-rose-50 px-2 py-1 rounded-md text-xs mr-1">S</span>
                                                        {{ $t->supplier->name ?? '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-semibold text-slate-700">
                                                    @if ($t->details->count() > 1)
                                                        <span
                                                            class="text-xs font-normal text-slate-500">{{ $t->details->count() }}
                                                            Item / Domba</span>
                                                    @else
                                                        {{ $t->details->first()?->sheep->code ?? '-' }} <span
                                                            class="text-xs font-normal text-slate-500">(x{{ $t->details->first()?->quantity ?? 1 }})</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-slate-800">
                                                    Rp {{ number_format($t->total_price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="relative flex justify-center" x-data="{ open: false }">
                                                        <button @click="open = !open" @click.outside="open = false"
                                                            class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all">
                                                            <svg width="3" height="15" viewBox="0 0 3 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.5C4.47035e-08 0.671573 0.671573 0 1.5 0C2.32843 4.47035e-08 3 0.671573 3 1.5C3 2.32843 2.32843 3 1.5 3C0.671573 3 0 2.32843 0 1.5ZM0 7.5C4.47035e-08 6.67157 0.671573 6 1.5 6C2.32843 6 3 6.67157 3 7.5C3 8.32843 2.32843 9 1.5 9C0.671573 9 0 8.32843 0 7.5ZM0 13.5C4.47035e-08 12.6716 0.671573 12 1.5 12C2.32843 12 3 12.6716 3 13.5C3 14.3284 2.32843 15 1.5 15C0.671573 15 0 14.3284 0 13.5Z" fill="#555555"/></svg>
                                                        </button>
                                                        <div x-show="open"
                                                            x-transition:enter="transition ease-out duration-150"
                                                            x-transition:enter-start="opacity-0 scale-95"
                                                            x-transition:enter-end="opacity-100 scale-100"
                                                            x-transition:leave="transition ease-in duration-100"
                                                            x-transition:leave-start="opacity-100 scale-100"
                                                            x-transition:leave-end="opacity-0 scale-95"
                                                            class="absolute right-0 top-9 z-30 w-36 rounded-xl bg-white shadow-xl border border-slate-100 overflow-hidden origin-top-right"
                                                            style="display:none;">
                                                            <a href="{{ route('transactions.show', $t) }}"
                                                                class="block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                                Detail
                                                            </a>
                                                            <a href="{{ route('transactions.edit', ['transaction' => $t, 'type' => $selectedType ?? $t->type]) }}"
                                                                class="block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                                Edit
                                                            </a>
                                                            <form action="{{ route('transactions.destroy', $t) }}" method="POST"
                                                                data-confirm-message="Apakah Anda yakin ingin menghapus transaksi ini?">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="w-full text-left px-4 py-2.5 text-sm font-semibold text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bg-slate-50 p-4 rounded-full mb-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-10 w-10 text-slate-300" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-slate-700">Tidak ada
                                                            transaksi ditemukan</h3>
                                                        <p class="text-sm text-slate-500 max-w-xs mx-auto mt-1">Silakan
                                                            tambahkan data transaksi jual atau beli domba.</p>
                                                        <a href="{{ route('transactions.create', $selectedType ? ['type' => $selectedType] : []) }}"
                                                            class="mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                                            + {{ $createLabel }}
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
                            {{ $transactions->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Flatpickr Assets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
        .flatpickr-months {
            background: white !important;
        }

        .flatpickr-innerContainer {
            padding: 10px;
        }

        .flatpickr-days {
            width: auto !important;
        }

        /* Custom Hover/Selected Days */
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
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


    @include('transactions.script')
</x-app-layout>
