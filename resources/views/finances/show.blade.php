<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-800">Detail Keuangan</h2>
                <p class="mt-1 text-sm text-slate-500">Ringkasan lengkap data pemasukan atau pengeluaran.</p>
            </div>

            <a href="{{ route('finances.index') }}"
                class="text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                Kembali ke daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12  bg-[#f0f6ff] min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8 max-w-4xl">
            <div
                class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl p-6 lg:p-10 border border-slate-100 relative overflow-hidden group">
                <div
                    class="absolute right-0 top-0 h-40 w-40 {{ $finance->type == 'income' ? 'bg-emerald-50/50' : 'bg-rose-50/50' }} rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110">
                </div>

                <div class="relative">
                    <!-- Top Header in Card -->
                    <div class="flex items-center gap-6 mb-12">
                        <div
                            class="p-5 {{ $finance->type == 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }} rounded-2xl shadow-inner">
                            @if ($finance->type == 'income')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-[#0f172a] tracking-tight">
                                {{ $finance->type == 'income' ? 'Pemasukan (Income)' : 'Pengeluaran (Expense)' }}
                            </h3>
                            <p class="text-[13px] text-gray-400 font-bold uppercase tracking-[0.15em] mt-1">Status Kas
                                Peternakan</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Left Big Info -->
                        <div
                            class="flex flex-col bg-slate-50/50 p-8 rounded-[32px] border border-gray-100/50 md:col-span-2">
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Total
                                Nilai Transaksi</span>
                            <span
                                class="text-5xl font-black {{ $finance->type == 'income' ? 'text-emerald-600' : 'text-rose-600' }} tracking-tighter">
                                <span class="text-2xl opacity-50 font-bold">{{ $finance->type == 'income' ? '+' : '-' }}
                                    Rp</span> {{ number_format($finance->amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Details Grid -->
                        <div class="flex flex-col space-y-1">
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-[0.15em]">Tanggal
                                Transaksi</span>
                            <span
                                class="text-slate-800 font-black text-[17px] tracking-tight">{{ $finance->created_at->format('l, d F Y') }}</span>
                            <span class="text-sm font-bold text-gray-400">{{ $finance->created_at->format('H:i') }}
                                WIB</span>
                        </div>

                        <div class="flex flex-col space-y-1">
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-[0.15em]">Rekening
                                Terkait</span>
                            <div class="flex flex-col">
                                <span
                                    class="text-slate-800 font-black text-[17px] tracking-tight">{{ $finance->bankAccount->bank_name ?? '-' }}</span>
                                <span
                                    class="text-sm font-bold text-blue-600/60 uppercase tracking-widest">{{ $finance->bankAccount->account_number ?? 'KAS KECIL / TUNAI' }}</span>
                            </div>
                        </div>

                        @if ($finance->transaction)
                            <div
                                class="flex flex-col md:col-span-2 bg-blue-50/30 p-6 rounded-2xl border border-blue-100/50">
                                <div class="flex justify-between items-center">
                                    <div class="flex flex-col gap-1">
                                        <span
                                            class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em]">Terkait
                                            Transaksi Domba</span>
                                        <h4 class="font-black text-slate-800 text-[16px] tracking-tight">
                                            {{ $finance->transaction->reference_number }}</h4>
                                    </div>
                                    <a href="{{ route('transactions.show', $finance->transaction) }}"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-600 text-xs font-black rounded-xl shadow-sm ring-1 ring-blue-200 hover:bg-blue-600 hover:text-white transition-all">
                                        LIHAT INVOICE
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col md:col-span-2 space-y-3">
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-[0.15em]">Catatan
                                Deskripsi</span>
                            <div
                                class="bg-slate-50/30 px-6 py-5 rounded-2xl border border-gray-100 text-slate-600 font-medium text-[15px] italic leading-relaxed">
                                "{{ $finance->description ?? 'Tidak ada rincian tambahan keterangan.' }}"
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Actions -->
                    <div class="mt-16 flex flex-col md:flex-row items-center gap-4 pt-8 border-t border-gray-100">
                        <a href="{{ route('finances.edit', $finance) }}"
                            class="w-full md:w-auto px-8 py-3.5 bg-blue-600 text-white text-sm font-black rounded-2xl hover:bg-blue-700 transition-all text-center shadow-lg shadow-gray-900/10">
                            Edit Catatan
                        </a>
                        <form action="{{ route('finances.destroy', $finance) }}" method="POST"
                            class="w-full md:w-auto" onsubmit="return confirm('Hapus permanen catatan arus kas ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full px-8 py-3.5 bg-rose-50 text-rose-600 text-sm font-black rounded-2xl hover:bg-rose-600 hover:text-white transition-all">
                                Hapus Data
                            </button>
                        </form>
                        <div class="md:ml-auto">
                            <a href="{{ route('finances.index') }}"
                                class="text-sm font-black text-gray-400 hover:text-blue-600 transition-colors uppercase tracking-widest">
                                Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
