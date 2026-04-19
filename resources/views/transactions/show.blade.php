<x-app-layout>
    <div class="min-h-screen bg-[#f8fbff] pb-12">
        <!-- Header Title -->
        <div class="px-8 pt-20 pb-6 no-print">
            <div class="flex items-end gap-2 text-gray-800">
                <h1 class="text-[32px] font-black text-[#0f172a] leading-none tracking-tight">
                    Faktur {{ ucfirst($transaction->type) }}
                </h1>
                <span class="text-[13px] font-medium mb-0.5 ml-1 text-gray-400 border-l-[1.5px] border-gray-300 pl-3">
                    {{ $transaction->reference_number }}
                </span>
            </div>
            
            <nav class="mt-3.5 flex items-center gap-1.5 text-[13px] font-bold text-gray-500 tracking-wide mb-8">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                <span class="text-gray-400 font-medium">&rsaquo;</span>
                <a href="{{ route('transactions.index', ['type' => $transaction->type]) }}" class="hover:text-blue-600 transition">Pembukuan</a>
                <span class="text-gray-400 font-medium">&rsaquo;</span>
                <span class="text-[#0f172a]">Faktur Transaksi</span>
            </nav>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Status / Tipe Transaksi -->
                <div class="relative">
                    <input type="text" readonly value="Tipe Transaksi : {{ ucfirst($transaction->type) }}" class="w-full rounded-xl border border-gray-200 text-sm px-4 py-3.5 outline-none text-[#64748b] font-bold bg-gray-50 shadow-[0_2px_4px_rgba(0,0,0,0.02)] cursor-default">
                </div>
                
                <!-- Dana Masuk ke / Info Bank -->
                <div class="relative">
                    @if($transaction->payment_method === 'Transfer Bank' && $transaction->bankAccount)
                        <div class="w-full rounded-xl border border-blue-100 text-sm px-4 py-3 outline-none bg-blue-50/50 shadow-[0_2px_4px_rgba(0,0,0,0.02)] cursor-default">
                            <span class="block text-[12px] font-black text-blue-400 uppercase tracking-widest ">Transfer: {{ $transaction->bankAccount->bank_name }}</span>
                        </div>
                    @else
                        <input type="text" readonly value="Metode: {{ $transaction->payment_method }}" class="w-full rounded-xl border border-gray-200 text-sm px-4 py-3.5 outline-none text-[#64748b] font-bold bg-white shadow-[0_2px_4px_rgba(0,0,0,0.02)] cursor-default">
                    @endif
                </div>
                
                <!-- Print Button -->
                <div class="relative flex justify-end">
                    <button onclick="window.print()" class="px-6 py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl shadow-sm hover:bg-gray-50 transition-all flex items-center gap-2">
                        <i data-feather="printer" class="w-4 h-4"></i> Cetak Faktur
                    </button>
                </div>
            </div>
        </div>
        
        <!-- INVOICE CARD -->
        <div class="px-8 mt-1" id="printable-area">
            <div class="bg-white rounded-2xl shadow-sm shadow-blue-900/5 ring-1 ring-gray-100 overflow-hidden">
                
                <!-- Print Header (Hanya tampil saat mode print) -->
                <div class="hidden print-header px-8 pt-8 pb-4 text-center border-b border-gray-100">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tighter">DOMBA LOKA</h2>
                    <p class="text-gray-500 font-medium mt-1">Sistem Manajemen Peternakan Cerdas</p>
                    <div class="mt-4 inline-block bg-gray-100 px-4 py-1.5 rounded-full">
                        <span class="text-sm font-extrabold text-gray-800">Faktur {{ ucfirst($transaction->type) }}</span>
                    </div>
                </div>

                <!-- Oleh & Pelanggan / Supplier -->
                <div class="px-8 py-6 border-b border-gray-100 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative">
                        <div class="hidden md:block absolute left-1/2 top-2 bottom-2 w-px border-l border-dashed border-gray-200"></div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-3 text-blue-500">
                                <span class="text-sm font-extrabold text-gray-500">Dikeluarkan Oleh :</span>
                            </div>
                            <input type="text" readonly value="{{ $transaction->kasir ?? 'Admin Domba Loka' }}" class="w-full border-none bg-transparent p-0 text-lg text-gray-800 font-black focus:ring-0">
                            
                            {{-- @if($transaction->payment_method === 'Transfer Bank' && $transaction->bankAccount)
                                <p class="text-[11px] font-bold text-blue-600 mt-0.5">
                                    {{ $transaction->bankAccount->bank_name }} ({{ $transaction->bankAccount->account_number }}) a.n {{ $transaction->bankAccount->account_name }}
                                </p>
                            @else
                                <p class="text-[11px] font-bold text-gray-400 mt-0.5">Metode Pembayaran: {{ $transaction->payment_method }}</p>
                            @endif --}}
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between mb-3 text-blue-500">
                                <span class="text-sm font-extrabold text-gray-500">{{ $transaction->type === 'penjualan' ? 'Pelanggan / Klien :' : 'Supplier :' }}</span>
                            </div>
                            <input type="text" readonly value="{{ $transaction->type === 'penjualan' ? ($transaction->customer->name ?? '-') : ($transaction->supplier->name ?? '-') }}" class="w-full border-none bg-transparent p-0 text-lg text-gray-800 font-black focus:ring-0">
                            <p class="text-[11px] font-bold text-gray-400 mt-0.5">{{ $transaction->type === 'penjualan' ? ($transaction->customer->phone ?? '-') : ($transaction->supplier->phone ?? '-') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Grey block: Tanggal & Tag -->
                <div class="px-8 py-5 border-b border-gray-100 bg-white">
                    <div class="bg-gray-50/70 rounded-2xl p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-6 border border-gray-100/50">
                        <div class="relative">
                            <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Tanggal transaksi</label>
                            <input type="text" readonly value="{{ $transaction->transaction_date->format('d M Y') }}" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2 bg-white text-gray-600 font-bold outline-none">
                        </div>
                        <div class="relative">
                            <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Jatuh Tempo</label>
                            <input type="text" readonly value="{{ $transaction->due_date->format('d M Y') }}" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2 bg-white text-gray-600 font-bold outline-none">
                        </div>
                        <div class="relative">
                            <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Nomor Referensi</label>
                            <input type="text" readonly value="{{ $transaction->reference_number }}" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2 bg-gray-50 text-gray-800 font-black outline-none">
                        </div>
                        <div class="relative">
                            <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm">Kandang / Lokasi</label>
                            <input type="text" readonly value="{{ $transaction->warehouse }}" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2 bg-white text-gray-600 font-bold outline-none">
                        </div>
                    </div>
                </div>

                <!-- Details Table -->
                <div class="p-8 pb-10">
                    <h3 class="text-sm font-extrabold text-black mb-6">Detail {{ ucfirst($transaction->type) }} :</h3>
                    
                    <div class="space-y-4">
                        @foreach($transaction->details as $detail)
                            <div class="flex flex-col md:flex-row md:items-center gap-3">
                                
                                <div class="hidden md:flex items-center gap-1.5 px-1">
                                    <div class="text-emerald-500 bg-emerald-50 p-1.5 rounded-full ring-1 ring-emerald-200">
                                        <i data-feather="check" class="w-3.5 h-3.5"></i>
                                    </div>
                                </div>
                                
                                <div class="flex-1 relative">
                                    <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[10px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm md:hidden">Nama Domba</label>
                                    <input type="text" readonly value="{{ $detail->sheep->code ?? '-' }} — {{ $detail->sheep->sheepType->name ?? '-' }}" class="w-full rounded-xl border-gray-200 text-sm text-gray-800 font-bold py-2.5 bg-gray-50/50 outline-none">
                                </div>

                                <div class="w-full md:w-20 relative">
                                    <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[10px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm md:hidden">Jml</label>
                                    <input type="text" readonly value="{{ $detail->quantity }}" class="w-full rounded-xl border-gray-200 text-sm text-gray-800 text-center font-bold py-2.5 bg-gray-50/50 outline-none">
                                </div>

                                <div class="w-full md:w-24 relative">
                                    <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[10px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm md:hidden">Satuan</label>
                                    <input type="text" readonly value="Ekor" class="w-full rounded-xl border-gray-200 text-sm text-gray-500 text-center py-2.5 bg-white outline-none">
                                </div>

                                <div class="w-full md:w-36 relative">
                                    <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[10px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm md:hidden">Harga Satuan</label>
                                    <input type="text" readonly value="Rp {{ number_format($detail->price, 0, ',', '.') }}" class="w-full rounded-xl border-gray-200 text-sm text-gray-800 font-bold py-2.5 px-3 bg-gray-50/50 text-right outline-none">
                                </div>

                                <div class="w-full md:w-24 relative">
                                    <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[10px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm md:hidden">Diskon</label>
                                    <input type="text" readonly value="{{ $detail->discount }}%" class="w-full rounded-xl border-gray-200 text-sm text-rose-500 font-bold text-center py-2.5 bg-white outline-none">
                                </div>

                                <div class="w-full md:w-40 relative">
                                    <label class="absolute -top-2.5 left-3 bg-white px-1.5 text-[10px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm md:hidden">Total</label>
                                    <input type="text" readonly value="Rp {{ number_format($detail->total_price, 0, ',', '.') }}" class="w-full rounded-xl border border-transparent bg-gray-50/80 text-sm font-black text-gray-900 outline-none py-2.5 px-3 text-right">
                                </div>
                                
                                <div class="w-10 flex border border-transparent md:block"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Summary Section -->
                <div class="flex flex-col lg:flex-row justify-end border-t border-dashed border-gray-200 bg-white">
                    
                    <!-- Left: Bukti Pembayaran + Tanda Terima (sejajar) -->
                    <div class="w-full lg:w-7/12 border-b lg:border-b-0 lg:border-r border-dashed border-gray-100 p-8">
                        <div class="flex gap-10">

                            <!-- Bukti Pembayaran -->
                            <div class="flex-1">
                                <h3 class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-4">Bukti Pembayaran</h3>
                                @if($transaction->attachment)
                                    <div x-data="{ open: false }" class="relative group inline-block">
                                        <!-- Thumbnail -->
                                        <div
                                            @click="open = true"
                                            class="relative cursor-zoom-in rounded-2xl overflow-hidden border border-gray-200 shadow-sm hover:border-blue-300 hover:shadow-md transition-all duration-300 w-48 h-32"
                                        >
                                            <img src="{{ asset('storage/' . $transaction->attachment) }}" alt="Bukti Pembayaran" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-blue-600/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <div class="bg-white/90 rounded-full p-2 shadow">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-wider">Klik untuk perbesar</p>

                                        <!-- Lightbox -->
                                        <div
                                            x-show="open"
                                            x-cloak
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            @click="open = false"
                                            @keydown.escape.window="open = false"
                                            class="fixed inset-0 z-[200] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm no-print"
                                        >
                                            <div @click.stop class="relative max-w-3xl w-full mx-4">
                                                <img src="{{ asset('storage/' . $transaction->attachment) }}" alt="Bukti Pembayaran" class="w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl">
                                                <button @click="open = false" class="absolute -top-3 -right-3 bg-white text-gray-700 hover:text-rose-600 p-2 rounded-full shadow-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                                <p class="text-center text-white/60 text-xs font-bold mt-3 tracking-wider uppercase">Bukti Pembayaran — {{ $transaction->reference_number }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl w-48 h-32 text-center">
                                        <svg class="w-7 h-7 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider leading-tight">Belum ada<br>bukti pembayaran</span>
                                    </div>
                                @endif
                                
                                @if($transaction->payment_method === 'Transfer Bank' && $transaction->bankAccount)
                                    <div class="mt-4 p-4 bg-blue-50/70 border border-blue-100 rounded-2xl w-48">
                                        <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-1 text-center">Rekening Tujuan</p>
                                        <p class="text-[12px] font-black text-blue-900 text-center">{{ $transaction->bankAccount->bank_name }}</p>
                                        <p class="text-[12px] font-bold text-blue-700 text-center tracking-wider mt-0.5">{{ $transaction->bankAccount->account_number }}</p>
                                        <p class="text-[10px] font-medium text-blue-500 text-center truncate mt-0.5">a.n {{ $transaction->bankAccount->account_name }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Divider vertikal -->
                            <div class="hidden md:block w-px border-l border-dashed border-gray-200 self-stretch"></div>

                            <!-- Tanda Terima -->
                            <div class="flex-1 flex flex-col items-center justify-between min-h-[9rem]">
                                <h3 class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-4 self-start">Tanda Terima</h3>
                                <div class="flex flex-col items-center justify-end flex-1 w-full pb-1">
                                    <div class="w-36 border-b-2 border-gray-300 mt-auto"></div>
                                    <p class="mt-2 text-xs font-bold text-gray-500 text-center">
                                        {{ $transaction->type === 'penjualan' ? ($transaction->customer->name ?? 'Pelanggan') : ($transaction->supplier->name ?? 'Supplier') }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Right: Computations -->
                    <div class="p-8 w-full lg:w-5/12 bg-gray-50/50">
                        <div class="space-y-3 text-sm text-gray-800">

                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-500">Subtotal</span>
                                <span class="font-bold text-gray-800">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                            </div>

                            @if($transaction->tax > 0)
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-500">Pajak ({{ number_format($transaction->tax, 0, ',', '.') }}%)</span>
                                <span class="font-bold text-amber-600">
                                    + Rp {{ number_format($transaction->subtotal * $transaction->tax / 100, 0, ',', '.') }}
                                </span>
                            </div>
                            @endif

                            @if($transaction->other_fees > 0)
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-500">Biaya Lainnya</span>
                                <span class="font-bold text-amber-600">+ Rp {{ number_format($transaction->other_fees, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between items-center border-t border-gray-200 pt-3 mt-1">
                                <span class="text-[17px] font-black text-black">Total Transaksi</span>
                                <span class="text-[20px] font-black text-[#03235b]">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                            </div>

                            @if($transaction->downpayment > 0)
                            <div class="flex justify-between items-center pt-1">
                                <span class="font-semibold text-gray-500 italic">Uang Muka / Dibayar</span>
                                <span class="font-bold text-emerald-600">- Rp {{ number_format($transaction->downpayment, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            @if($transaction->sisa > 0)
                            <div class="flex justify-between items-center bg-rose-50 -mx-8 px-8 py-4 border-t border-rose-100 mt-2">
                                <span class="text-[15px] font-black text-slate-800">Sisa Tagihan</span>
                                <span class="text-[20px] font-black text-rose-600">Rp {{ number_format($transaction->sisa, 0, ',', '.') }}</span>
                            </div>
                            @else
                            <div class="flex justify-between items-center bg-emerald-50 -mx-8 px-8 py-4 border-t border-emerald-100 mt-2">
                                <span class="text-[15px] font-black text-slate-800">Status</span>
                                <span class="inline-flex items-center gap-1.5 text-[13px] font-black text-emerald-700 bg-emerald-100 px-3 py-1.5 rounded-full">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    LUNAS
                                </span>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>


            </div>
            
            <div class="mt-6 flex justify-between items-center no-print">
                <a href="{{ route('transactions.index', ['type' => $transaction->type]) }}" class="text-blue-500 hover:text-blue-600 font-bold text-sm flex items-center gap-2 transition px-4 py-2 hover:bg-blue-50 rounded-xl">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali ke Pembukuan
                </a>
                
                <a href="{{ route('transactions.edit', $transaction->id) }}" class="px-8 py-3 rounded-xl bg-[#0f172a] hover:bg-black text-white text-sm font-bold shadow-sm shadow-slate-500/20 transition-all flex items-center gap-2">
                    <i data-feather="edit-2" class="w-4 h-4"></i> Edit / Revisi Transaksi
                </a>
            </div>
            
        </div>
    </div>

    <!-- Print CSS -->
    <x-slot name="scripts">
        <style>
            @media print {
                body * { visibility: hidden; }
                #printable-area, #printable-area * { visibility: visible; }
                #printable-area { position: absolute; left: 0; top: 0; width: 100%; padding: 0; margin: 0; }
                .no-print { display: none !important; }
                .print-header { display: block !important; padding-top: 1rem; }
                .print-signature { display: flex !important; }
                
                /* Keep gray backgrounds when printing */
                * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                
                /* Remove shadow around card */
                #printable-area > div { box-shadow: none !important; ring: none !important; border: none !important; }
            }
        </style>
    </x-slot>
</x-app-layout>
