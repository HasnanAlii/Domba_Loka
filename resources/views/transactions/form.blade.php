<x-app-layout>
    <!-- Alpine.js Transaction Form Data -->
    <div x-data="transactionForm()">
        <form action="{{ $action }}" method="POST" class="min-h-screen bg-[#f8fbff] pb-12">
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
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800">Terdapat {{ $errors->count() }} kesalahan pengisian form:</h3>
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
                    <h1 class="text-[32px] font-black text-[#0f172a] leading-none tracking-tight" x-text="type === 'penjualan' ? 'Penjualan Produk' : 'Pembelian Produk'">Penjualan Produk</h1>
                    <span class="text-[13px] font-medium mb-0.5 ml-1 text-gray-400 border-l-[1.5px] border-gray-300 pl-3">Buat Transaksi Baru</span>
                </div>
                <nav class="mt-3.5 flex items-center gap-1.5 text-[13px] font-bold text-gray-500 tracking-wide mb-8">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                    <span class="text-gray-400 font-medium">&rsaquo;</span>
                    <a href="{{ route('transactions.index') }}" class="hover:text-blue-600 transition">Pembukuan</a>
                    <span class="text-gray-400 font-medium">&rsaquo;</span>
                    <span class="text-[#0f172a]" x-text="type === 'penjualan' ? 'Penjualan Produk' : 'Pembelian Produk'">Penjualan Produk</span>
                </nav>

                <!-- Floating Filters Outside White Card -->
            <!-- Hidden (tidak ikut grid) -->
            <input type="hidden" id="type" name="type" x-model="type">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Metode Bayar -->
                <div class="relative">
                    <select name="payment_method" x-model="paymentMethod" class="w-full rounded-xl border border-gray-200 text-sm px-4 py-3.5 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-[#64748b] font-medium hover:border-gray-300 cursor-pointer bg-white appearance-none shadow-[0_2px_4px_rgba(0,0,0,0.02)]" required>
                        <option value="Tunai">Tunai (Cash)</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                    </select>
                    @error('payment_method')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>

                <!-- Rekening Bank -->
                <div class="relative">
                    <select name="bank_account_id" x-model="bankAccountId" :disabled="paymentMethod !== 'Transfer Bank'" class="w-full rounded-xl border border-gray-200 text-sm px-4 py-3.5 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-[#64748b] font-medium hover:border-gray-300 cursor-pointer bg-white appearance-none shadow-[0_2px_4px_rgba(0,0,0,0.02)] disabled:cursor-not-allowed disabled:bg-gray-100">
                        <option value="">Pilih Rekening Bank</option>
                        @foreach($bankAccounts as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->bank_name }} - {{ $bank->account_number }}</option>
                        @endforeach
                    </select>
                    @error('bank_account_id')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror
                </div>

            </div>
            </div>

            <div class="px-8 mt-1">
                <div class="bg-white rounded-2xl shadow-sm shadow-blue-900/5 ring-1 ring-gray-100 overflow-hidden">

                    <!-- Oleh & Pelanggan -->
                    <div class="px-8 py-6 border-b border-gray-100 bg-white">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative">
                            <!-- Center vertical divider -->
                            <div class="hidden md:block absolute left-1/2 top-2 bottom-2 w-px border-l border-dashed border-gray-200"></div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-3 text-blue-500">
                                    <span class="text-sm font-extrabold text-gray-500">Oleh :</span>
                                    <button type="button" class="text-blue-500 hover:text-blue-600 p-1 bg-blue-50 rounded-md">
                                        <i data-feather="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                                <input type="text" readonly value="{{ auth()->user()->name }}" class="w-full border-none bg-transparent p-0 text-sm text-gray-800 font-bold focus:ring-0">
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-3 text-blue-500">
                                    <span class="text-sm font-extrabold text-gray-500" x-text="type === 'penjualan' ? 'Pelanggan :' : 'Supplier :'"></span>
                                    <button type="button" class="text-blue-500 hover:text-blue-600 p-1 bg-blue-50 rounded-md">
                                        <i data-feather="edit" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                                
                                <div x-show="type === 'penjualan'">
                                    <select name="customer_id" :disabled="type !== 'penjualan'" class="w-full border-none bg-transparent p-0 text-sm text-gray-800 font-bold focus:ring-0 cursor-pointer">
                                        <option value="">Pilih Pelanggan / Customer</option>
                                        @foreach($customers as $c)
                                            <option value="{{ $c->id }}" @selected(old('customer_id', $transaction->customer_id) == $c->id)>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="type === 'pembelian'" style="display: none;">
                                    <select name="supplier_id" :disabled="type !== 'pembelian'" class="w-full border-none bg-transparent p-0 text-sm text-gray-800 font-bold focus:ring-0 cursor-pointer">
                                        <option value="">Pilih Supplier</option>
                                        @foreach($suppliers as $s)
                                            <option value="{{ $s->id }}" @selected(old('supplier_id', $transaction->supplier_id) == $s->id)>{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grey block: Tanggal & Tag -->
                    <div class="px-8 py-5 border-b border-gray-100 bg-white">
                        <div class="bg-gray-50/70 rounded-2xl p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-6 border border-gray-100/50">
                            
                            <div class="relative">
                                <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Tanggal transaksi</label>
                                <input type="date" name="transaction_date" value="{{ old('transaction_date', isset($transaction->transaction_date) ? $transaction->transaction_date->format('Y-m-d') : date('Y-m-d')) }}" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2 bg-white text-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium">
                            </div>
                            
                            <div class="relative">
                                <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Jatuh Tempo</label>
                                <input type="date" name="due_date" value="{{ old('due_date', isset($transaction->due_date) ? $transaction->due_date->format('Y-m-d') : date('Y-m-d')) }}" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2 bg-white text-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium">
                            </div>
                            
                            <div class="relative">
                                <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Nomor Referensi</label>
                                <input type="text" name="reference_number" value="{{ old('reference_number', $transaction->reference_number ?? 'SJ-'.date('Ymd').'-'.strtoupper(Str::random(4))) }}" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2 bg-gray-50 text-gray-500 font-bold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 cursor-not-allowed" readonly>
                            </div>

                            <div class="relative">
                                <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Kandang </label>
                                <select name="warehouse" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2.5 bg-white text-gray-700 font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none cursor-pointer">
                                    <option value="Pusat Kandang Dombaloka" @selected(old('warehouse', $transaction->warehouse ?? '') == 'Pusat Kandang Dombaloka')>Pusat Kandang Dombaloka</option>
                                    {{-- <option value="Kandang Cabang Utama" @selected(old('warehouse', $transaction->warehouse ?? '') == 'Kandang Cabang Utama')>Kandang Cabang Utama</option> --}}
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- Details Table -->
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-sm font-extrabold text-black">Detail Transaksi :</h3>
                            <button x-show="type === 'pembelian'" type="button" @click="isSheepModalOpen = true" class="text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition shadow-sm border border-blue-100 flex items-center gap-1.5 focus:ring-2 focus:ring-blue-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Input Data Domba Baru
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <template x-for="(item, index) in items" :key="item.id">
                                <div class="flex flex-col md:flex-row md:items-center gap-3">
                                    
                                    <!-- Handle Option icons -->
                                    <div class="hidden md:flex items-center gap-1.5 px-1">
                                        <button type="button" class="text-gray-400 hover:text-blue-500 bg-gray-50 p-1.5 rounded-full ring-1 ring-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="3"></circle>
                                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- Domba Select -->
                                    <div class="flex-1">
                                        <select x-model="item.sheep_id" :name="'details['+index+'][sheep_id]'" @change="updatePrice(item)" class="w-full rounded-xl border-gray-200 text-sm text-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-2.5 cursor-pointer" required>
                                            <option value="">Pilih Domba</option>
                                            <template x-for="sheep in dbSheep" :key="sheep.id">
                                                <option :value="sheep.id" x-text="sheep.code + ' — ' + (sheep.sheep_type?.name || '-')"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Jumlah -->
                                    <div class="w-full md:w-24">
                                        <input type="number" x-model.number="item.qty" @input="calculateTotals()" :name="'details['+index+'][quantity]'" min="1" class="w-full rounded-xl border-gray-200 text-sm text-gray-600 text-center focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-2.5" placeholder="Jumlah" required>
                                    </div>

                                    <!-- Satuan -->
                                    <div class="w-full md:w-28">
                                        <select class="w-full rounded-xl border-gray-200 bg-gray-50/50 text-sm text-gray-400 outline-none py-2.5 cursor-not-allowed">
                                            <option>Ekor</option>
                                        </select>
                                    </div>

                                    <!-- Harga Satuan -->
                                    <div class="w-full md:w-36">
                                        <input type="text" x-model="item.price" x-mask:dynamic="$money($input, ',', '.')" @input="calculateTotals()" :name="'details['+index+'][price]'" class="w-full rounded-xl border-gray-200 text-sm text-gray-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-2.5 px-3" placeholder="Harga Satuan (Rp)" required>
                                    </div>

                                    <!-- Diskon -->
                                    <div class="w-full md:w-24">
                                        <input type="number" x-model.number="item.discount" @input="if(item.discount > 100) item.discount = 100; if(item.discount < 0) item.discount = 0; calculateTotals()" :name="'details['+index+'][discount]'" min="0" max="100" class="w-full rounded-xl border-gray-200 text-sm text-gray-600 text-center focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 py-2.5" placeholder="Diskon %">
                                    </div>

                                    <!-- Total Harga -->
                                    <div class="w-full md:w-40">
                                        <input type="text" :value="formatMoney(itemTotal(item))" readonly class="w-full rounded-xl border border-transparent bg-gray-50/80 text-sm font-bold text-gray-700 outline-none py-2.5 px-3 cursor-default placeholder-gray-400" placeholder="Total Harga (Rp)">
                                    </div>

                                    <!-- Trash Delete -->
                                    <div class="w-10 flex justify-center">
                                        <button type="button" @click="removeItem(index)" class="text-rose-500 hover:text-rose-600 hover:bg-rose-50 p-2 rounded-xl transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="addItem()" class="mt-5 flex items-center gap-1.5 text-sm font-bold text-blue-600 hover:text-blue-700 transition">
                            <span class="text-lg leading-none mb-0.5">+</span> Tambah Item
                        </button>
                    </div>

                    <!-- Footer Summary Section -->
                    <div class="flex flex-col lg:flex-row justify-end border-t border-dashed border-gray-200 bg-white">
                        <!-- Right: Calculations Summary -->
                        <div class="p-8 w-full lg:w-6/12 xl:w-5/12">
                            <div class="space-y-4 text-sm text-gray-800">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold text-gray-500">Subtotal Item</span>
                                    <span class="text-[15px] font-extrabold text-gray-700" x-text="formatMoney(subtotal)"></span>
                                    <input type="hidden" name="subtotal" :value="subtotal">
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold text-gray-500">Pajak (Rp)</span>
                                    <input type="text" name="tax" x-model="tax" x-mask:dynamic="$money($input, ',', '.')" @input="calculateTotals()" class="w-32 rounded-lg border-gray-200 text-sm text-right px-3 py-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold text-gray-500">Biaya Lainnya (Rp)</span>
                                    <input type="text" name="other_fees" x-model="otherFees" x-mask:dynamic="$money($input, ',', '.')" @input="calculateTotals()" class="w-32 rounded-lg border-gray-200 text-sm text-right px-3 py-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                    <span class="text-[17px] font-extrabold text-black">Grand Total</span>
                                    <span class="text-[19px] font-extrabold text-[#03235b]" x-text="formatMoney(total)"></span>
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <span class="text-[15px] font-semibold text-gray-500">Uang Muka / Dibayar (Rp)</span>
                                    <input type="text" name="downpayment" x-model="downpayment" x-mask:dynamic="$money($input, ',', '.')" @input="calculateTotals()" class="w-36 rounded-lg border-gray-200 text-[15px] text-right font-bold text-gray-700 px-3 py-2 border focus:border-gray-400 focus:ring-0 bg-white">
                                </div>
                                <div class="flex justify-between items-center -mx-4 px-4 py-3">
                                    <span class="text-[16px] font-extrabold text-black">Sisa Tagihan</span>
                                    <span class="text-[19px] font-extrabold text-[#0f172a]" x-text="formatMoney(sisa)"></span>
                                    <input type="hidden" name="sisa" :value="sisa">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Layout Footer / Bottom Action Buttons -->
                    <div class="bg-white p-6 flex justify-end items-center gap-3 border-t border-gray-100 rounded-b-2xl shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.02)]">
                        <a href="{{ route('transactions.index') }}" class="px-8 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold shadow-sm shadow-rose-500/20 transition-all focus:ring-2 focus:ring-offset-1 focus:ring-rose-500 text-center">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold shadow-sm shadow-blue-500/30 transition-all focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">
                            Simpan Transaksi
                        </button>
                    </div>

                </div>
            </div>
            
            <!-- Hidden Input for Database mapping required currently -->
            <input type="hidden" name="total_price" :value="total">

            <!-- Modal Tambah Domba -->
            <div x-show="isSheepModalOpen" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" style="display: none;">
                <div @click.away="isSheepModalOpen = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <div class="flex items-center gap-2">
                            <div class="bg-blue-100 text-blue-600 p-1.5 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <h3 class="text-[17px] font-black text-gray-800">Tambah Domba Baru</h3>
                        </div>
                        <button type="button" @click="isSheepModalOpen = false" class="text-gray-400 hover:text-rose-500 bg-gray-100 p-1.5 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Code -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 mb-1.5 uppercase tracking-wider">Kode Domba</label>
                                <input type="text" x-model="newSheep.code" class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4" placeholder="Masukkan Kode Domba">
                            </div>
                            <!-- Type -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 mb-1.5 uppercase tracking-wider">Jenis Domba</label>
                                <select x-model="newSheep.type_id" class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4 cursor-pointer">
                                    <option value="">Pilih Jenis</option>
                                    @foreach($sheepTypes as $st)
                                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Weight -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-400 mb-1.5 uppercase tracking-wider">Berat (Kg)</label>
                                    <input type="number" x-model.number="newSheep.weight" min="1" class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4" placeholder="Misal: 45">
                                </div>
                                <!-- Condition -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-400 mb-1.5 uppercase tracking-wider">Kondisi Fisik</label>
                                    <select x-model="newSheep.condition" class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4 cursor-pointer">
                                        <option value="Sehat">Sehat</option>
                                        <option value="Sakit">Sakit</option>
                                        <option value="Dalam Masa Rawat">Dalam Masa Rawat</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Price -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-400 mb-1.5 uppercase tracking-wider">Harga Taksiran Pembelian (Rp)</label>
                                <input type="text" x-model="newSheep.price" x-mask:dynamic="$money($input, ',', '.')" class="w-full rounded-xl border-gray-200 text-sm font-bold text-gray-700 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none py-2.5 px-4" placeholder="Masukan Harga dlm Rupiah">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50/50">
                        <button type="button" @click="isSheepModalOpen = false" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all hover:text-gray-800 focus:ring-2 focus:ring-gray-200">
                            Batal
                        </button>
                        <button type="button" @click="saveNewSheep()" :disabled="isSavingSheep" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all focus:ring-4 focus:ring-blue-500/30 shadow-md shadow-blue-500/20 disabled:bg-blue-400 flex items-center justify-center gap-2 min-w-[140px]">
                            <svg x-show="isSavingSheep" class="animate-spin -ml-1 mr-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="isSavingSheep ? 'Menyimpan...' : 'Simpan Domba'">Simpan Domba</span>
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>


    @include('transactions.script')
</x-app-layout>
