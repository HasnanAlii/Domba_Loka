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
                
                <!-- Dana Masuk ke -->
                <div class="relative">
                    <input type="text" readonly value="{{ $transaction->payment_method }}" class="w-full rounded-xl border border-gray-200 text-sm px-4 py-3.5 outline-none text-[#64748b] font-bold bg-white shadow-[0_2px_4px_rgba(0,0,0,0.02)] cursor-default">
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
                            <input type="text" readonly value="Admin Domba Loka" class="w-full border-none bg-transparent p-0 text-lg text-gray-800 font-black focus:ring-0">
                            <p class="text-[11px] font-bold text-gray-400 mt-0.5">Metode Pembayaran: {{ $transaction->payment_method }}</p>
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
                    <!-- Blank area for signature if printed -->
                    <div class="hidden print-signature lg:flex w-full lg:w-7/12 items-end p-8 text-center text-sm font-bold text-gray-400">
                        <div class="flex flex-col items-center justify-end h-full">
                            <div class="mb-16">TANDA TERIMA</div>
                            <div class="w-48 border-b-2 border-gray-300"></div>
                            <div class="mt-2">{{ $transaction->type === 'penjualan' ? ($transaction->customer->name ?? 'Pelanggan') : ($transaction->supplier->name ?? 'Supplier') }}</div>
                        </div>
                    </div>

                    <!-- Computations -->
                    <div class="p-8 w-full lg:w-5/12 bg-gray-50/50 border-l border-gray-100">
                        <div class="space-y-4 text-sm text-gray-800">
                            <div class="flex justify-between items-center py-1">
                                <span class="text-[17px] font-bold text-gray-600">Subtotal</span>
                                <span class="text-[17px] font-bold text-gray-800">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                                <span class="text-[20px] font-black text-black">Total Transaksi</span>
                                <span class="text-[24px] font-black text-[#03235b]">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                            </div>
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
