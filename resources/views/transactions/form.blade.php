<x-app-layout>
    <style>
        /* Sembunyikan tombol naik/turun di input number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <!-- Alpine.js Transaction Form Data -->
    <div x-data="transactionForm()">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data"
            class="min-h-screen  bg-[#f0f6ff] px-5 pb-12">
            @csrf
            @if (isset($method) && $method !== 'POST')
                @method($method)
            @endif

            <!-- Header Title -->
            <div class="px-8 pt-20 pb-6">
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800">Terdapat {{ $errors->count() }} kesalahan
                                    pengisian form:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-end gap-2 text-gray-800">
                    <h1 class="text-[32px] font-black text-[#0f172a] leading-none tracking-tight"
                        x-text="type === 'penjualan' ? 'Penjualan Produk' : 'Pembelian Produk'">Penjualan Produk</h1>
                    <span
                        class="text-[13px] font-medium mb-0.5 ml-1 text-gray-400 border-l-[1.5px] border-gray-300 pl-3">Buat
                        Transaksi Baru</span>
                </div>
                <nav class="mt-3.5 flex items-center gap-1.5 text-[13px] font-bold text-gray-500 tracking-wide mb-8">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                    <span class="text-gray-400 font-medium">&rsaquo;</span>
                    <a href="{{ route('transactions.index') }}" class="hover:text-blue-600 transition">Pembukuan</a>
                    <span class="text-gray-400 font-medium">&rsaquo;</span>
                    <span class="text-[#0f172a]"
                        x-text="type === 'penjualan' ? 'Penjualan Produk' : 'Pembelian Produk'">Penjualan Produk</span>
                </nav>

                <!-- Floating Filters Outside White Card -->
                <!-- Hidden (tidak ikut grid) -->
                <input type="hidden" id="type" name="type" x-model="type">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Metode Bayar -->
                    <!-- Metode Bayar -->
                    <div class="relative" x-data="{ open: false }">
                        <input type="hidden" name="payment_method" :value="paymentMethod">

                        <!-- Trigger -->
                        <div @click="open = !open"
                            class="relative flex items-center bg-white border border-slate-200 rounded-xl transition-all duration-300 cursor-pointer hover:border-blue-300 shadow-sm"
                            :class="open ? 'border-blue-400 ring-4 ring-blue-500/10' : ''">
                            <div class="w-full px-4 py-3 text-[16px] text-slate-700 font-medium" x-text="paymentMethod">
                            </div>

                            <div class="px-3.5 py-3 border-l border-slate-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-300"
                                    :class="open ? 'rotate-180 text-blue-500' : ''" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-cloak @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute z-50 w-full mt-1.5 bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/[0.02]">
                            <div class="py-1">
                                <template x-for="option in ['Tunai', 'Transfer Bank']" :key="option">
                                    <div @click="paymentMethod = option; open = false"
                                        class="px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-all duration-200 flex items-center justify-between group">
                                        <span class="text-[14px] font-bold tracking-tight transition-colors"
                                            :class="paymentMethod === option ? 'text-blue-600' :
                                                'text-slate-700 group-hover:text-blue-600'"
                                            x-text="option"></span>

                                        <div x-show="paymentMethod === option"
                                            class="w-4 h-4 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-2.5 h-2.5 text-blue-600" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @error('payment_method')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rekening Bank -->
                    <div class="relative" x-data="{ open: false }">
                        <!-- Overlay Grey out ONLY FOR THIS FORM -->
                        <div x-show="paymentMethod !== 'Transfer Bank'"
                            class="absolute inset-0 z-[20] bg-slate-50/70 rounded-xl cursor-not-allowed border border-slate-200/50"
                            x-transition.opacity></div>

                        <input type="hidden" name="bank_account_id" :value="bankAccountId">

                        <!-- Trigger -->
                        <div @click="open = !open"
                            class="relative flex items-center bg-white border border-slate-200 rounded-xl transition-all duration-300 cursor-pointer hover:border-blue-300 shadow-sm"
                            :class="open ? 'border-blue-400 ring-4 ring-blue-500/10' : ''">
                            <div class="w-full px-4 py-3 text-[16px] font-medium"
                                :class="selectedBank ? 'text-slate-700' : 'text-slate-400'"
                                x-text="selectedBank ? selectedBank.bank_name : 'Pilih Rekening Bank...'">
                            </div>

                            <div class="px-3.5 py-3 border-l border-slate-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-slate-400 transition-transform duration-300"
                                    :class="open ? 'rotate-180 text-blue-500' : ''" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-cloak @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute z-50 w-full mt-1.5 bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/[0.02]">
                            <div class="py-1 max-h-60 overflow-y-auto">
                                <template x-for="bank in dbBanks.filter(b => b.bank_name !== 'Kas Tunai')"
                                    :key="bank.id">
                                    <div @click="bankAccountId = bank.id; syncSelectedBank(bank.id); open = false"
                                        class="px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-all duration-200 flex items-center justify-between group">
                                        <div class="flex flex-col">
                                            <span class="text-[14px] font-bold tracking-tight"
                                                :class="bankAccountId == bank.id ? 'text-blue-600' :
                                                    'text-slate-700 group-hover:text-blue-600'"
                                                x-text="bank.bank_name"></span>
                                            {{-- <span class="text-[11px] text-slate-400 font-medium" x-text="bank.account_number"></span> --}}
                                        </div>

                                        <div x-show="bankAccountId == bank.id"
                                            class="w-4 h-4 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-2.5 h-2.5 text-blue-600" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @error('bank_account_id')
                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>



                </div>
            </div>

            <div class="px-8 mt-1">
                <div class="bg-white rounded-2xl shadow-sm shadow-blue-900/5 ring-1 ring-gray-100 overflow-hidden">

                    <!-- Oleh & Pelanggan -->
                    <div class="px-8 py-6 border-b border-gray-100 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative">
                            <!-- Center vertical divider -->
                            <div
                                class="hidden md:block absolute left-1/2 top-2 bottom-2 w-px border-l border-dashed border-gray-200">
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-3 text-blue-500">
                                    <span class="text-xl font-extrabold text-gray-500">Oleh :</span>
                                    <button type="button"
                                        class="text-blue-500 hover:text-blue-600 p-1 bg-blue-50 rounded-md">
                                        <i data-feather="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                                <input type="text" name="kasir" readonly
                                    value="{{ old('kasir', $transaction->kasir ?? auth()->user()->name) }}"
                                    class="w-full border-none bg-transparent p-0 text-md text-gray-800 font-bold focus:ring-0">
                                <p class="text-[16px] text-gray-400 mt-0.5">{{ auth()->user()->email }}</p>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-3 text-blue-500">
                                    <span class="text-xl font-extrabold text-gray-500"
                                        x-text="type === 'penjualan' ? 'Pelanggan :' : 'Supplier :'"></span>
                                    <button type="button" @click="isPartyModalOpen = true"
                                        class="text-blue-500 hover:text-blue-600 p-1 bg-blue-50 rounded-md">
                                        <i data-feather="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>

                                <div @click="isPartyModalOpen = true" class="cursor-pointer group">
                                    <template x-if="type === 'penjualan'">
                                        <div>
                                            <input type="hidden" name="customer_id" :value="customerId">
                                            <div x-show="selectedCustomer">
                                                <p class="text-md text-gray-800 font-bold group-hover:text-blue-600 transition-colors"
                                                    x-text="selectedCustomer?.name"></p>
                                                <p class="text-[16px] text-gray-400 mt-0.5"
                                                    x-text="selectedCustomer?.phone || 'No Phone'"></p>
                                            </div>
                                            <div x-show="!selectedCustomer" class="py-2">
                                                <p class="text-gray-400 font-medium italic group-hover:text-blue-400">
                                                    Pilih Pelanggan...</p>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="type === 'pembelian'">
                                        <div>
                                            <input type="hidden" name="supplier_id" :value="supplierId">
                                            <div x-show="selectedSupplier">
                                                <p class="text-md text-gray-800 font-bold group-hover:text-blue-600 transition-colors"
                                                    x-text="selectedSupplier?.name"></p>
                                                <p class="text-[16px] text-gray-400 mt-0.5"
                                                    x-text="selectedSupplier?.phone || 'No Phone'"></p>
                                            </div>
                                            <div x-show="!selectedSupplier" class="py-2">
                                                <p class="text-gray-400 font-medium italic group-hover:text-blue-400">
                                                    Pilih Supplier...</p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grey block: Tanggal & Tag -->
                    <div class="px-8 py-5 border-b border-gray-100 bg-white">
                        <div
                            class="bg-slate-50/70 rounded-2xl p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-6 border border-gray-100/50">

                            <div class="relative">
                                <label
                                    class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Tanggal
                                    transaksi</label>
                                <input type="date" name="transaction_date"
                                    value="{{ old('transaction_date', isset($transaction->transaction_date) ? $transaction->transaction_date->format('Y-m-d') : date('Y-m-d')) }}"
                                    class="w-full rounded-xl border-gray-200 text-[16px] px-4 py-3 bg-white text-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium tracking-tight">
                            </div>

                            <div class="relative">
                                <label
                                    class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Jatuh
                                    Tempo</label>
                                <input type="date" name="due_date"
                                    value="{{ old('due_date', isset($transaction->due_date) ? $transaction->due_date->format('Y-m-d') : date('Y-m-d')) }}"
                                    class="w-full rounded-xl border-gray-200 text-[16px] px-4 py-3 bg-white text-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium tracking-tight">
                            </div>

                            <div class="relative">
                                <label
                                    class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Nomor
                                    Referensi</label>
                                <input type="text" name="reference_number"
                                    value="{{ old('reference_number', $transaction->reference_number ?? 'SJ-' . date('Ymd') . '-' . strtoupper(Str::random(4))) }}"
                                    class="w-full rounded-xl border-gray-200 text-[16px] px-4 py-3 bg-slate-50 text-gray-500 font-bold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 cursor-not-allowed"
                                    readonly>
                            </div>

                            <!-- Kandang -->
                            <div class="relative group" x-data="{ open: false, selected: '{{ old('warehouse', $transaction->warehouse ?? 'Pusat Kandang Dombaloka') }}' }">
                                <label
                                    class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm z-10 transition-colors group-focus-within:text-blue-500">Kandang
                                </label>
                                <input type="hidden" name="warehouse" :value="selected">

                                <!-- Trigger -->
                                <div @click="open = !open"
                                    class="relative flex items-center bg-white border border-slate-200 rounded-xl transition-all duration-300 cursor-pointer hover:border-blue-300 shadow-sm"
                                    :class="open ? 'border-blue-400 ring-4 ring-blue-500/10' : ''">
                                    <div class="w-full px-4 py-3 text-[16px] text-slate-700 font-semibold"
                                        x-text="selected"></div>

                                    <div
                                        class="px-3.5 py-3 border-l border-slate-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-300"
                                            :class="open ? 'rotate-180 text-blue-500' : ''"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Dropdown Menu -->
                                <div x-show="open" x-cloak @click.outside="open = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="absolute z-50 w-full mt-1.5 bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/[0.02]">
                                    <div class="py-1">
                                        <template x-for="option in ['Pusat Kandang Dombaloka']"
                                            :key="option">
                                            <div @click="selected = option; open = false"
                                                class="px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-all duration-200 flex items-center justify-between group">
                                                <span class="text-[14px] font-bold tracking-tight transition-colors"
                                                    :class="selected === option ? 'text-blue-600' :
                                                        'text-slate-700 group-hover:text-blue-600'"
                                                    x-text="option"></span>
                                                <div x-show="selected === option"
                                                    class="w-4 h-4 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-2.5 h-2.5 text-blue-600" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Details Table -->
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-sm font-extrabold text-black">Detail Transaksi :</h3>
                            <button x-show="type === 'pembelian'" type="button" @click="isSheepModalOpen = true"
                                class="text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition shadow-sm border border-blue-100 flex items-center gap-1.5 focus:ring-2 focus:ring-blue-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Input Data Domba Baru
                            </button>
                        </div>

                        <div class="space-y-4">
                            @php
                                $sheepOptions = $sheep
                                    ->map(
                                        fn($s) => [
                                            'id' => $s->id,
                                            'name' => $s->code . ' — ' . ($s->sheepType->name ?? '-'),
                                        ],
                                    )
                                    ->toArray();
                            @endphp

                            <template x-for="(item, index) in items" :key="item.id">
                                <div class="flex flex-col md:flex-row md:items-center gap-3">

                                    <!-- Handle Option icons -->
                                    <div class="hidden md:flex items-center gap-1.5 px-1">
                                        <button type="button"
                                            class="text-gray-400 hover:text-blue-500 bg-slate-50 p-1.5 rounded-full ring-1 ring-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="3"></circle>
                                                <path
                                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Pilih Domba -->
                                    <div class="flex-1"
                                        @@selected="item.sheep_id = $event.detail.id; updatePrice(item)">
                                        <x-searchable-dropdown placeholder="Cari Domba..." :options="$sheepOptions"
                                            limit="5" :showFooter="false" />
                                        <input type="hidden" :name="'details[' + index + '][sheep_id]'"
                                            :value="item.sheep_id">
                                    </div>

                                    <!-- Jumlah -->
                                    <div class="w-full md:w-24">
                                        <input type="number" x-model.number="item.qty" @input="calculateTotals()"
                                            :name="'details[' + index + '][quantity]'" min="1"
                                            class="w-full rounded-xl border-gray-200 text-[16px] text-gray-600 text-center focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-3"
                                            placeholder="Jumlah" required>
                                    </div>

                                    <!-- Satuan -->
                                    <div class="w-full md:w-28">
                                        <select disabled
                                            class="w-full rounded-xl border-gray-200 bg-slate-50/50 text-[16px] text-gray-400 outline-none py-3 cursor-not-allowed">
                                            <option>Ekor</option>
                                        </select>
                                    </div>

                                    <!-- Harga Satuan (otomatis dari harga domba) -->
                                    <div class="w-full md:w-40">
                                        <input type="text" x-model="item.price"
                                            :name="'details[' + index + '][price]'" readonly
                                            class="w-full rounded-xl border-gray-200 text-[16px] text-gray-600 bg-slate-50/80 cursor-not-allowed focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-3 px-3"
                                            placeholder="Harga Satuan (Rp)" required>
                                    </div>

                                    <!-- Diskon -->
                                    <div class="w-full md:w-32">
                                        <input type="number" :value="item.discount || ''"
                                            @input="item.discount = $event.target.value; if(item.discount > 100) item.discount = 100; if(item.discount < 0) item.discount = 0; calculateTotals()"
                                            :name="'details[' + index + '][discount]'" min="0" max="100"
                                            class="w-full rounded-xl border-gray-200 text-[16px] text-gray-600 text-center focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-3"
                                            placeholder="Diskon %">
                                    </div>

                                    <!-- Total Harga -->
                                    <div class="w-full md:w-40">
                                        <input type="text" :value="formatMoney(itemTotal(item))" readonly
                                            class="w-full rounded-xl border border-transparent bg-slate-50/80 text-[16px] font-bold text-gray-700 outline-none py-3 px-3 cursor-default placeholder-gray-400"
                                            placeholder="Total Harga (Rp)">
                                    </div>

                                    <!-- Trash Delete -->
                                    <div class="w-10 flex justify-center">
                                        <button type="button" @click="removeItem(index)"
                                            class="text-rose-500 hover:text-rose-600 hover:bg-rose-50 p-2 rounded-xl transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2.2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="addItem()"
                            class="mt-5 flex items-center gap-1.5 text-sm font-bold text-blue-600 hover:text-blue-700 transition">
                            <span class="text-lg leading-none mb-0.5">+</span> Tambah Item
                        </button>
                    </div>

                    <!-- Footer Summary Section -->
                    <div
                        class="flex flex-col lg:flex-row justify-between border-t border-dashed border-gray-200 bg-white">

                        <!-- Left Adjustments -->
                        <div
                            class="p-8 w-full lg:w-5/12 border-b lg:border-b-0 lg:border-r border-dashed border-gray-100">
                            <div class="space-y-6">
                                <h3 class="text-[13px] font-black text-gray-400 uppercase tracking-widest mb-4">
                                    Penyesuaian & Dokumentasi</h3>

                                <div class="flex justify-between items-center group mb-6">
                                    <span class="text-[16px] font-semibold text-gray-600">Bukti Pembayaran</span>

                                    <div class="relative w-48">
                                        <!-- Hidden Input -->
                                        <input type="file" name="attachment" id="attachment" class="hidden"
                                            x-ref="proofInput" @change="previewProof($event)" accept="image/*">

                                        <!-- Preview / Upload Button -->
                                        <div @click="$refs.proofInput.click()"
                                            class="relative w-full h-[52px] border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-between px-4 transition-all hover:border-blue-400 hover:bg-blue-50/30 cursor-pointer overflow-hidden"
                                            :class="proofPreview ? 'border-solid border-blue-100 bg-blue-50/20' : ''">
                                            <template x-if="!proofPreview">
                                                <div class="flex items-center justify-between w-full">
                                                    <span
                                                        class="text-[11px] font-black text-gray-400 tracking-wider">UPLOAD
                                                        BUKTI...</span>
                                                    <svg class="w-4 h-4 text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                            </template>

                                            <template x-if="proofPreview">
                                                <div
                                                    class="relative w-full h-full flex items-center justify-center p-1">
                                                    <img :src="proofPreview"
                                                        class="h-full w-full object-cover rounded-lg">
                                                    <div
                                                        class="absolute inset-0 bg-blue-600/10 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                                                        <span
                                                            class="text-[9px] font-black text-blue-700 bg-white/90 px-2 py-1 rounded shadow-sm flex items-center gap-1">
                                                            <svg class="w-2.5 h-2.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2.5"
                                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                                </path>
                                                            </svg>
                                                            GANTI
                                                        </span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Clear Button -->
                                        <button x-show="proofPreview" @click.stop="removeProof()" type="button"
                                            class="absolute -top-2 -right-2 bg-white text-rose-500 p-1 rounded-full shadow-md hover:text-rose-600 transition-all z-10 border border-gray-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>

                                        @error('attachment')
                                            <p
                                                class="mt-1 text-[11px] text-rose-500 font-bold px-1 uppercase tracking-tighter">
                                                {{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Info Rekening Bank (tampil jika Transfer Bank & bank dipilih) -->
                                <div x-show="paymentMethod === 'Transfer Bank' && selectedBank" x-cloak
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-1"
                                    class="flex items-center gap-4 bg-blue-50/70 border border-blue-100 rounded-2xl px-5 py-4">
                                    <!-- Icon bank -->
                                    <div
                                        class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-0.5">
                                            Transfer Ke</p>
                                        <p class="text-[15px] font-black text-blue-900 truncate"
                                            x-text="selectedBank?.bank_name ?? ''"></p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <p class="text-[13px] font-bold text-blue-700 tracking-widest"
                                                x-text="selectedBank?.account_number ?? ''"></p>
                                            <span class="text-blue-300">·</span>
                                            <p class="text-[12px] font-semibold text-blue-500 truncate"
                                                x-text="selectedBank?.account_name ?? ''"></p>
                                        </div>
                                    </div>

                                    <!-- Copy button -->
                                    <button type="button"
                                        @click="navigator.clipboard.writeText(selectedBank?.account_number ?? ''); $el.classList.add('text-emerald-600'); setTimeout(() => $el.classList.remove('text-emerald-600'), 1500)"
                                        class="flex-shrink-0 text-blue-400 hover:text-blue-600 transition-colors p-1.5 rounded-lg hover:bg-blue-100"
                                        title="Salin nomor rekening">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="flex justify-between items-start">
                                    <div class="flex flex-col">
                                        <span class="text-[16px] font-semibold text-gray-600">Pajak (%)</span>
                                        <span class="text-[11px] text-blue-500 font-bold"
                                            x-text="'(Nominal: ' + formatMoney(taxNominal) + ')'"></span>
                                    </div>
                                    <input type="number" name="tax" x-model.number="tax"
                                        @input="if(tax > 100) tax = 100; if(tax < 0) tax = 0; calculateTotals()"
                                        min="0" max="100"
                                        class="w-48 rounded-xl border-gray-200 text-[16px] text-right px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 tracking-tight transition-all"
                                        placeholder="0 %">
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-[16px] font-semibold text-gray-600">Biaya Lainnya (Rp)</span>
                                    <input type="text" name="other_fees" x-model="otherFees"
                                        x-mask:dynamic="$money($input, ',', '.')" @input="calculateTotals()"
                                        class="w-48 rounded-xl border-gray-200 text-[16px] text-right px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 tracking-tight transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Right: Calculations Summary -->
                        <div class="p-8 w-full lg:w-5/12 bg-slate-50/30">
                            <div class="space-y-4 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold text-gray-400">Subtotal Item</span>
                                    <span class="text-[16px] font-extrabold text-gray-700"
                                        x-text="formatMoney(subtotal)"></span>
                                    <input type="hidden" name="subtotal" :value="subtotal">
                                </div>

                                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                    <span class="text-[17px] font-black text-slate-800">Grand Total</span>
                                    <span class="text-[20px] font-black text-blue-900"
                                        x-text="formatMoney(total)"></span>
                                </div>

                                <div class="flex justify-between items-center pt-4">
                                    <span class="text-[15px] font-bold text-gray-500 italic">Uang Muka / Dibayar</span>
                                    <input type="text" name="downpayment" x-model="downpayment"
                                        x-mask:dynamic="$money($input, ',', '.')" @input="calculateTotals()"
                                        class="w-48 rounded-xl border-2 border-blue-100 text-[16px] text-right font-black text-blue-700 px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 bg-white shadow-sm transition-all tracking-tight">
                                </div>

                                <div
                                    class="flex justify-between items-center pt-4 bg-white -mx-8 px-8 py-4 border-t border-gray-100">
                                    <span class="text-[17px] font-black text-slate-900">Sisa Tagihan</span>
                                    <span class="text-[22px] font-black text-rose-600"
                                        x-text="formatMoney(sisa)"></span>
                                    <input type="hidden" name="sisa" :value="sisa">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Layout Footer / Bottom Action Buttons -->
                    <div
                        class="bg-white p-6 flex justify-end items-center gap-3 border-t border-gray-100 rounded-b-2xl shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.02)]">
                        <a href="{{ route('transactions.index') }}"
                            class="px-8 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold shadow-sm shadow-rose-500/20 transition-all focus:ring-2 focus:ring-offset-1 focus:ring-rose-500 text-center">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-8 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold shadow-sm shadow-blue-500/30 transition-all focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">
                            Simpan Transaksi
                        </button>
                    </div>

                </div>
            </div>

            <!-- Hidden Input for Database mapping required currently -->
            <input type="hidden" name="total_price" :value="total">

            <!-- Modal Tambah Domba -->
            <div x-show="isSheepModalOpen" x-transition.opacity
                class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm"
                style="display: none;">
                <div @click.away="isSheepModalOpen = false"
                    class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <div class="bg-blue-100 text-blue-600 p-1.5 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-[17px] font-black text-gray-800">Tambah Domba Baru</h3>
                        </div>
                        <button type="button" @click="isSheepModalOpen = false"
                            class="text-gray-400 hover:text-rose-500 bg-slate-50 p-1.5 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Code -->
                            <div>
                                <label
                                    class="block text-[11px] font-bold text-gray-400 mb-1.5 uppercase tracking-wider">Kode
                                    Domba</label>
                                <input type="text" x-model="newSheep.code"
                                    class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4"
                                    placeholder="Masukkan Kode Domba">
                            </div>
                            <!-- Type -->
                            <div>
                                <label
                                    class="block text-[11px] font-bold text-gray-400 mb-1.5 uppercase tracking-wider">Jenis
                                    Domba</label>
                                <select x-model="newSheep.type_id"
                                    class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4 cursor-pointer">
                                    <option value="">Pilih Jenis</option>
                                    @foreach ($sheepTypes as $st)
                                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Weight -->
                                <div>
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 mb-1.5 uppercase tracking-wider">Berat
                                        (Kg)</label>
                                    <input type="number" x-model.number="newSheep.weight" min="1"
                                        class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4"
                                        placeholder="Misal: 45">
                                </div>
                                <!-- Condition -->
                                <div>
                                    <label
                                        class="block text-[11px] font-bold text-gray-400 mb-1.5 uppercase tracking-wider">Kondisi
                                        Fisik</label>
                                    <select x-model="newSheep.condition"
                                        class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4 cursor-pointer">
                                        <option value="Sehat">Sehat</option>
                                        <option value="Sakit">Sakit</option>
                                        <option value="Dalam Masa Rawat">Dalam Masa Rawat</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Price -->
                            <div>
                                <label
                                    class="block text-[11px] font-bold text-gray-400 mb-1.5 uppercase tracking-wider">Harga
                                    Taksiran Pembelian (Rp)</label>
                                <input type="text" x-model="newSheep.price"
                                    x-mask:dynamic="$money($input, ',', '.')"
                                    class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4"
                                    placeholder="Masukan Harga dlm Rupiah">
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-slate-50/50">
                        <button type="button" @click="isSheepModalOpen = false"
                            class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-slate-50 transition-all hover:text-gray-800 focus:ring-2 focus:ring-gray-200">
                            Batal
                        </button>
                        <button type="button" @click="saveNewSheep()" :disabled="isSavingSheep"
                            class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all focus:ring-4 focus:ring-blue-500/30 shadow-md shadow-blue-500/20 disabled:bg-blue-400 flex items-center justify-center gap-2 min-w-[140px]">
                            <svg x-show="isSavingSheep" class="animate-spin -ml-1 mr-1 h-4 w-4 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span x-text="isSavingSheep ? 'Menyimpan...' : 'Simpan Domba'">Simpan Domba</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Pilih Pelanggan/Supplier -->
            <div x-show="isPartyModalOpen" x-transition.opacity
                class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm"
                style="display: none;">
                <div @click.away="isPartyModalOpen = false"
                    class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <div class="bg-blue-100 text-blue-600 p-1.5 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-[17px] font-black text-gray-800"
                                x-text="type === 'penjualan' ? 'Pilih Pelanggan' : 'Pilih Supplier'">Pilih Data</h3>
                        </div>
                        <button type="button" @click="isPartyModalOpen = false"
                            class="text-gray-400 hover:text-rose-500 bg-slate-50 p-1.5 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Search & List -->
                    <div class="p-6">
                        <div x-data="{ search: '' }">
                            <input type="text" x-model="search"
                                class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4 mb-4"
                                :placeholder="type === 'penjualan' ? 'Cari Pelanggan...' : 'Cari Supplier...'">

                            <div class="max-h-[300px] overflow-y-auto space-y-2 pr-1">
                                <template x-if="type === 'penjualan'">
                                    <template
                                        x-for="item in dbCustomers.filter(c => c.name.toLowerCase().includes(search.toLowerCase()))"
                                        :key="item.id">
                                        <div @click="selectParty(item)"
                                            class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:border-blue-400 hover:bg-blue-50/50 cursor-pointer transition-all group">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-500"
                                                    x-text="item.name.charAt(0)"></div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-700 group-hover:text-blue-700"
                                                        x-text="item.name"></p>
                                                    <p class="text-[11px] text-gray-400 font-medium"
                                                        x-text="item.phone || '-'"></p>
                                                </div>
                                            </div>
                                            <svg x-show="customerId == item.id" class="w-5 h-5 text-blue-500"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </template>
                                </template>

                                <template x-if="type === 'pembelian'">
                                    <template
                                        x-for="item in dbSuppliers.filter(s => s.name.toLowerCase().includes(search.toLowerCase()))"
                                        :key="item.id">
                                        <div @click="selectParty(item)"
                                            class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:border-blue-400 hover:bg-blue-50/50 cursor-pointer transition-all group">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-500"
                                                    x-text="item.name.charAt(0)"></div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-700 group-hover:text-blue-700"
                                                        x-text="item.name"></p>
                                                    <p class="text-[11px] text-gray-400 font-medium"
                                                        x-text="item.phone || '-'"></p>
                                                </div>
                                            </div>
                                            <svg x-show="supplierId == item.id" class="w-5 h-5 text-blue-500"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center bg-slate-50/50">
                        <button type="button"
                            @click.stop="isPartyModalOpen = false; if(type === 'penjualan') { isAddCustomerModalOpen = true } else { isAddSupplierModalOpen = true }"
                            class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1.5 uppercase tracking-wider">
                            <span class="text-lg">+</span> <span
                                x-text="type === 'penjualan' ? 'Tambah Pelanggan Baru' : 'Tambah Supplier Baru'"></span>
                        </button>
                        <button type="button" @click="isPartyModalOpen = false"
                            class="px-5 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-slate-50 transition-all">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Pelanggan -->
            <div x-show="isAddCustomerModalOpen" x-transition.opacity
                class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm"
                style="display: none;">
                <div @click.away="isAddCustomerModalOpen = false"
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-blue-50">
                    <div
                        class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gradient-to-r from-blue-50/50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-600 text-white p-2 rounded-xl shadow-lg shadow-blue-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-black text-gray-800">Pelanggan Baru</h3>
                        </div>
                        <button type="button" @click="isAddCustomerModalOpen = false"
                            class="text-gray-400 hover:text-rose-500 bg-slate-50 p-2 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-8 space-y-5">
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama
                                Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" x-model="newCustomer.name" placeholder="Masukkan Nama "
                                class="w-full rounded-2xl border-gray-100 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all py-3.5 px-5 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nomor
                                WhatsApp/HP <span class="text-rose-500">*</span></label>
                            <input type="text" x-model="newCustomer.phone" placeholder="Masukkan Nomor WhatsApp/HP"
                                class="w-full rounded-2xl border-gray-100 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all py-3.5 px-5 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Email
                                (Opsional)</label>
                            <input type="email" x-model="newCustomer.email" placeholder="Masukkan Email"
                                class="w-full rounded-2xl border-gray-100 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all py-3.5 px-5 outline-none">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Alamat
                                (Opsional)</label>
                            <textarea x-model="newCustomer.address" rows="3" placeholder="Masukkan Alamat"
                                class="w-full rounded-2xl border-gray-100 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all py-3.5 px-5 outline-none"></textarea>
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-slate-50 border-t border-gray-100 flex flex-col gap-3">
                        <button type="button" @click="saveNewParty()" :disabled="isSavingParty"
                            class="w-full py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2 group disabled:opacity-50">
                            <span x-show="!isSavingParty" class="flex items-center gap-2">
                                Simpan Pelanggan <i data-feather="arrow-right"
                                    class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            </span>
                            <span x-show="isSavingParty" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                        <button type="button" @click="isAddCustomerModalOpen = false"
                            class="w-full py-3 text-sm font-bold text-gray-400 hover:text-gray-600 transition-all">
                            Batalkan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Supplier -->
            <div x-show="isAddSupplierModalOpen" x-transition.opacity
                class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm"
                style="display: none;">
                <div @click.away="isAddSupplierModalOpen = false"
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all border border-amber-50">
                    <div
                        class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gradient-to-r from-amber-50/50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="bg-amber-600 text-white p-2 rounded-xl shadow-lg shadow-amber-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-black text-gray-800">Supplier Baru</h3>
                        </div>
                        <button type="button" @click="isAddSupplierModalOpen = false"
                            class="text-gray-400 hover:text-rose-500 bg-slate-50 p-2 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-8 space-y-5">
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama
                                Perusahaan/Supplier <span class="text-rose-500">*</span></label>
                            <input type="text" x-model="newSupplier.name" placeholder="Masukkan Nama Perusahaan/Supplier"
                                class="w-full rounded-2xl border-gray-100 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 px-5 outline-none">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nomor
                                Kontak/HP <span class="text-rose-500">*</span></label>
                            <input type="text" x-model="newSupplier.phone" placeholder="Masukkan Nomor Kontak/HP"
                                class="w-full rounded-2xl border-gray-100 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 px-5 outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Email
                                (Opsional)</label>
                            <input type="email" x-model="newSupplier.email" placeholder="Masukkan Email"
                                class="w-full rounded-2xl border-gray-100 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 px-5 outline-none">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Alamat
                                (Opsional)</label>
                            <textarea x-model="newSupplier.address" rows="3" placeholder="Masukkan Alamat"
                                class="w-full rounded-2xl border-gray-100 text-sm font-bold text-gray-700 bg-slate-50 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all py-3.5 px-5 outline-none"></textarea>
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-slate-50 border-t border-gray-100 flex flex-col gap-3">
                        <button type="button" @click="saveNewParty()" :disabled="isSavingParty"
                            class="w-full py-4 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm shadow-xl shadow-amber-200 transition-all flex items-center justify-center gap-2 group disabled:opacity-50">
                            <span x-show="!isSavingParty" class="flex items-center gap-2">
                                Simpan Supplier <i data-feather="arrow-right"
                                    class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            </span>
                            <span x-show="isSavingParty" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                        <button type="button" @click="isAddSupplierModalOpen = false"
                            class="w-full py-3 text-sm font-bold text-gray-400 hover:text-gray-600 transition-all">
                            Batalkan
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>


    @include('transactions.script')
</x-app-layout>
