<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-extrabold text-2xl leading-tight tracking-tight text-gray-800">
                {{ __('Manajemen Keuangan') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Keuangan</span>
            </nav>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#f0f6ff] px-10 py-12">
        <div class="mx-auto space-y-8 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Pemasukan -->
                <div
                    class="group relative overflow-hidden rounded-3xl bg-white p-7 shadow-sm shadow-blue-900/5 ring-1 ring-gray-100 transition-all hover:shadow-xl hover:shadow-emerald-900/5 hover:-translate-y-1">
                    <div
                        class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-bl-full bg-emerald-50/50 transition-transform group-hover:scale-125">
                    </div>
                    <div class="relative flex h-full flex-col justify-between">
                        <div class="mb-5 flex items-center gap-4">
                            <div class="rounded-2xl bg-emerald-100/50 p-3.5 text-emerald-600 shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div class="text-[11px] font-black uppercase tracking-[0.15em] text-gray-400">Total
                                Pemasukan</div>
                        </div>
                        <div class="text-3xl font-black tracking-tight text-emerald-600">
                            <span class="text-lg font-bold opacity-60">Rp</span>
                            {{ number_format($income, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Pengeluaran -->
                <div
                    class="group relative overflow-hidden rounded-3xl bg-white p-7 shadow-sm shadow-blue-900/5 ring-1 ring-gray-100 transition-all hover:shadow-xl hover:shadow-rose-900/5 hover:-translate-y-1">
                    <div
                        class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-bl-full bg-rose-50/50 transition-transform group-hover:scale-125">
                    </div>
                    <div class="relative flex h-full flex-col justify-between">
                        <div class="mb-5 flex items-center gap-4">
                            <div class="rounded-2xl bg-rose-100/50 p-3.5 text-rose-600 shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6" />
                                </svg>
                            </div>
                            <div class="text-[11px] font-black uppercase tracking-[0.15em] text-gray-400">Total
                                Pengeluaran</div>
                        </div>
                        <div class="text-3xl font-black tracking-tight text-rose-600">
                            <span class="text-lg font-bold opacity-60">Rp</span>
                            {{ number_format($expense, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Total Saldo -->
                <div
                    class="group relative overflow-hidden rounded-3xl bg-[#0f172a] p-7 shadow-xl shadow-slate-900/20 transition-all hover:-translate-y-1">
                    <div class="absolute right-0 top-0 -mr-8 -mt-8 h-40 w-40 rounded-full bg-blue-500/10 blur-2xl">
                    </div>
                    <div class="relative flex h-full flex-col justify-between">
                        <div class="mb-5 flex items-center gap-4">
                            <div class="rounded-2xl bg-white/10 p-3.5 backdrop-blur-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="text-[11px] font-black uppercase tracking-[0.15em] text-gray-400">Total Saldo
                                Kas</div>
                        </div>
                        <div class="text-3xl font-black tracking-tight text-white">
                            <span class="text-lg font-bold opacity-40">Rp</span>
                            {{ number_format($balance, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Table Card -->
            <div class="rounded-[32px] bg-white shadow-sm shadow-blue-900/5 ring-1 ring-gray-100">
                <div class="p-8 lg:p-12">
                    <div class="relative z-[250] mb-10 flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                        <div>
                            <h3 class="text-xl font-black text-[#0f172a] tracking-tight">Riwayat Transaksi Keuangan</h3>
                            <p class="mt-1.5 text-sm font-medium text-gray-400">Arus kas masuk dan keluar peternakan secara real-time.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative" x-data="{ open: false }" :class="open ? 'z-[100]' : 'z-10'">
                                <button @click="open = !open" @click.outside="open = false" type="button"
                                    class="group inline-flex items-center gap-2 px-5 py-3 bg-amber-400 text-black text-sm font-bold rounded-2xl shadow-lg shadow-amber-400/30 hover:bg-amber-500 hover:shadow-amber-500/40 transition-all duration-300 transform hover:-translate-y-0.5">
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
                                    <a href="{{ route('finances.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'excel'])) }}"
                                        class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors group/item">
                                        Export Excel
                                    </a>
                                    <div class="border-t border-slate-50"></div>
                                    <!-- PDF -->
                                    <a href="{{ route('finances.index') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'pdf'])) }}"
                                        class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition-colors group/item">
                                        Export PDF
                                    </a>
                                </div>
                            </div>
                            
                            <a href="{{ route('finances.create') }}"
                                class="group inline-flex items-center gap-2.5 rounded-2xl bg-blue-600 px-7 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-600/20 transition-all duration-300 hover:bg-[#03235b] hover:shadow-blue-900/40 hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Catat Transaksi Kas
                            </a>
                        </div>
                    </div>

                    <div
                        class="relative z-[200] mb-6 overflow-visible rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <form id="filter-form" method="GET" action="{{ route('finances.index') }}"
                            class="relative z-[200] grid grid-cols-1 gap-4 overflow-visible md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 items-end">
                            @php
                                $bankAccountOptions = $bankAccounts
                                    ->map(
                                        fn($b) => [
                                            'id' => (string) $b->id,
                                            'name' => $b->bank_name . ' - ' . $b->account_number,
                                        ],
                                    )
                                    ->prepend(['id' => '', 'name' => 'Semua Rekening'])
                                    ->values()
                                    ->toArray();

                                $customerOptions = collect($customers)
                                    ->map(
                                        fn($c) => [
                                            'id' => (string) $c->name,
                                            'name' => $c->name,
                                        ],
                                    )
                                    ->prepend(['id' => '', 'name' => 'Semua Pelanggan'])
                                    ->values()
                                    ->toArray();

                                $supplierOptions = collect($suppliers)
                                    ->map(
                                        fn($s) => [
                                            'id' => (string) $s->name,
                                            'name' => $s->name,
                                        ],
                                    )
                                    ->prepend(['id' => '', 'name' => 'Semua Supplier'])
                                    ->values()
                                    ->toArray();
                            @endphp

                            <!-- 1. Rentang Tanggal -->
                            <div class="flex flex-col">
                                <label for="filter_date_range"
                                    class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Rentang
                                    Tanggal</label>
                                <div class="relative group">
                                    <input type="text" id="filter_date_range" name="date_range"
                                        value="{{ $filters['date_range'] ?? '' }}" placeholder="Pilih rentang..."
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] pr-10 text-[13px] font-bold text-slate-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 cursor-pointer shadow-sm min-h-[38px]">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 0   0-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    <div
                                        class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor font-bold">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Tipe -->
                            <div class="flex flex-col">
                                <label
                                    class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Tipe</label>
                                <div x-data="{
                                    open: false,
                                    selected: '{{ ($filters['type'] ?? '') === 'income' ? 'Masuk' : (($filters['type'] ?? '') === 'expense' ? 'Keluar' : 'Semua') }}',
                                    options: [
                                        { label: 'Semua', value: '' },
                                        { label: 'Masuk', value: 'income' },
                                        { label: 'Keluar', value: 'expense' }
                                    ],
                                    select(option) {
                                        this.selected = option.label;
                                        this.$refs.type_input.value = option.value;
                                        this.open = false;
                                    }
                                }" class="relative">
                                    <input type="hidden" name="type" x-ref="type_input"
                                        value="{{ $filters['type'] ?? '' }}">
                                    <div @click="open = !open"
                                        class="flex items-center justify-between w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] text-[13px] font-bold cursor-pointer hover:border-blue-500 transition-all shadow-sm min-h-[38px]"
                                        :class="open ? 'border-blue-500 ring-4 ring-blue-500/10' : ''">
                                        <span x-text="selected" class="leading-none"
                                            :class="selected === 'Semua' ? 'text-slate-400' : 'text-slate-600'"></span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform"
                                            :class="open ? 'rotate-180 text-blue-500' : ''" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 9l-7 7-7-7" />
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
                                                :class="selected === option.label ? 'text-blue-600 bg-blue-50/30' :
                                                    'text-slate-600 group-hover:text-blue-600'">
                                                <span x-text="option.label"></span>
                                                <div x-show="selected === option.label"
                                                    class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Rekening -->
                            <div class="flex flex-col">
                                <label
                                    class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Rekening</label>
                                <x-searchable-dropdown name="bank_account_id" id="filter_bank_account_id"
                                    placeholder="Semua Rekening..." :options="$bankAccountOptions" :value="$filters['bank_account_id'] ?? ''"
                                    :showFooter="false" :compact="true" />
                            </div>

                            <!-- 4. Pelanggan -->
                            <div class="flex flex-col">
                                <label
                                    class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Pelanggan</label>
                                <x-searchable-dropdown name="customer" id="filter_customer" placeholder="Semua..."
                                    :options="$customerOptions" :value="$filters['customer'] ?? ''" :showFooter="false" :compact="true" />
                            </div>

                            <!-- 5. Supplier -->
                            <div class="flex flex-col">
                                <label
                                    class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Supplier</label>
                                <x-searchable-dropdown name="supplier" id="filter_supplier" placeholder="Semua..."
                                    :options="$supplierOptions" :value="$filters['supplier'] ?? ''" :showFooter="false" :compact="true" />
                            </div>

                            <!-- 6. Terapkan -->
                            <div class="flex flex-col">
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-black text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700 hover:shadow-blue-600/40 transform hover:-translate-y-0.5 min-h-[38px]">
                                    Terapkan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="relative z-0 rounded-2xl border border-gray-100 bg-white shadow-sm">
                        <div class="overflow-visible">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-slate-50/50 border-b border-gray-100">
                                        <th
                                            class="px-6 py-5 text-center text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                                            ID</th>
                                        <th
                                            class="px-6 py-5 text-center text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-5 text-left text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                                            Rekening & Referensi</th>
                                        <th
                                            class="px-6 py-5 text-center text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                                            Tipe</th>
                                        <th
                                            class="px-6 py-5 text-right text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                                            Nominal</th>
                                        <th
                                            class="px-6 py-5 text-left text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                                            Keterangan</th>
                                        <th
                                            class="px-6 py-5 text-center text-[11px] font-black uppercase tracking-[0.2em] text-gray-400">
                                            Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-50">
                                    @forelse ($finances as $key => $finance)
                                        <tr class="group hover:bg-blue-50/30 transition-colors">
                                            <td class="px-6 py-5 text-center text-sm font-bold text-gray-300">
                                                #{{ $finances->firstItem() + $key }}</td>
                                            <td class="px-6 py-5 text-center">
                                                <span
                                                    class="text-sm font-bold text-slate-600 block">{{ $finance->created_at->format('d M') }}</span>
                                                <span
                                                    class="text-[10px] font-black text-gray-300 uppercase leading-none">{{ $finance->created_at->format('Y') }}</span>
                                            </td>
                                            <td class="px-6 py-5">
                                                <span
                                                    class="text-[15px] font-black text-slate-800 block">{{ $finance->bankAccount->bank_name ?? 'Kas Tunai' }}</span>
                                                @if ($finance->transaction)
                                                    <a href="{{ route('transactions.show', $finance->transaction) }}"
                                                        class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-1 rounded-md bg-blue-50 text-[10px] font-black text-blue-600 uppercase tracking-wider hover:bg-blue-600 hover:text-white transition-all">
                                                        {{ $finance->transaction->reference_number }}
                                                    </a>
                                                @else
                                                    <span class="text-[11px] font-medium text-gray-400">External
                                                        Flow</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                @if ($finance->type === 'income')
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full ring-1 ring-emerald-500/20">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                        </svg>
                                                        Masuk
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-widest text-rose-600 bg-rose-50 px-3 py-1.5 rounded-full ring-1 ring-rose-500/20">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                        </svg>
                                                        Keluar
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-5 text-right">
                                                <span
                                                    class="text-[16px] font-black {{ $finance->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                    {{ $finance->type === 'income' ? '+' : '-' }} Rp
                                                    {{ number_format($finance->amount, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5">
                                                <p class="text-sm font-medium text-slate-500 max-w-[200px] truncate"
                                                    title="{{ $finance->description }}">
                                                    {{ $finance->description ?? '-' }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-5 text-center">
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
                                                        <a href="{{ route('finances.show', $finance) }}"
                                                            class="block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                            Detail
                                                        </a>
                                                        <a href="{{ route('finances.edit', $finance) }}"
                                                            class="block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('finances.destroy', $finance) }}" method="POST"
                                                            data-confirm-message="Hapus catatan arus kas ini?">
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
                                        <!-- Keep the original empty state structure but polished -->
                                        <tr>
                                            <td colspan="7" class="px-6 py-20 text-center">
                                                <div class="flex flex-col items-center">
                                                    <div class="bg-slate-50 p-6 rounded-[32px] mb-4">
                                                        <svg class="h-12 w-12 text-gray-200" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.5"
                                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-lg font-black text-gray-800">Riwayat Masih Kosong
                                                    </h3>
                                                    <p class="text-sm text-gray-400 mt-1 max-w-xs">Belum ada aktivitas
                                                        arus kas untuk periode ini.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($finances->hasPages())
                        <div class="mt-8">
                            {{ $finances->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
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

        .flatpickr-months {
            background: white !important;
        }

        .flatpickr-innerContainer {
            padding: 10px;
        }

        .flatpickr-days {
            width: auto !important;
        }

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

    @include('finances.script', ['page' => 'index'])
</x-app-layout>
