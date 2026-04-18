<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-800">Detail Keuangan</h2>
                <p class="mt-1 text-sm text-slate-500">Ringkasan lengkap data pemasukan atau pengeluaran.</p>
            </div>

            <a href="{{ route('finances.index') }}" class="text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                Kembali ke daftar
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl rounded-3xl border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/60 lg:p-8">
            <dl class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Jenis</dt>
                    <dd class="mt-2 text-base font-bold text-slate-800">{{ $finance->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Jumlah</dt>
                    <dd class="mt-2 text-base font-bold {{ $finance->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">Rp {{ number_format($finance->amount, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Rekening</dt>
                    <dd class="mt-2 text-base text-slate-800">{{ $finance->bankAccount->bank_name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-slate-500">Referensi Transaksi</dt>
                    <dd class="mt-2 text-base text-slate-800">{{ $finance->transaction->reference_number ?? '-' }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-sm font-semibold text-slate-500">Deskripsi</dt>
                    <dd class="mt-2 text-base text-slate-800">{{ $finance->description ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-app-layout><x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Detail Kas Keuangan') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('finances.index') }}" class="hover:text-blue-600 cursor-pointer transition">Keuangan</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail Kas</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8 max-w-4xl">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl p-6 lg:p-10 border border-slate-100 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-40 w-40 {{ $finance->type == 'income' ? 'bg-emerald-50/50' : 'bg-rose-50/50' }} rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-5 mb-8 border-b border-slate-100 pb-6">
                        <div class="p-4 {{ $finance->type == 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }} rounded-2xl">
                            @if($finance->type == 'income')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $finance->type == 'income' ? 'Pemasukan (Income)' : 'Pengeluaran (Expense)' }}
                            </h3>
                            <p class="text-sm text-slate-500 font-medium">Data transaksi kas peternakan</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">Tanggal Transaksi</span>
                            <span class="text-slate-700 font-medium text-lg">{{ $finance->created_at->format('l, d F Y - H:i') }}</span>
                        </div>
                        
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">Rekening Terkait</span>
                            <div class="flex items-center gap-2">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-md text-sm font-bold ring-1 ring-blue-600/20">{{ $finance->bankAccount->bank_name ?? '-' }}</span>
                                <span class="text-slate-600 font-medium">{{ $finance->bankAccount->account_number ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col bg-slate-50 p-6 rounded-2xl border border-slate-100 md:col-span-2">
                            <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Total Nilai Transaksi</span>
                            <span class="text-4xl font-extrabold {{ $finance->type == 'income' ? 'text-emerald-600' : 'text-rose-600' }} font-mono tracking-tighter">
                                {{ $finance->type == 'income' ? '+' : '-' }} Rp {{ number_format($finance->amount, 0, ',', '.') }}
                            </span>
                        </div>

                        @if($finance->transaction)
                            <div class="flex flex-col md:col-span-2 bg-indigo-50/40 p-6 rounded-2xl border border-indigo-100">
                                <span class="text-sm font-bold text-indigo-500 uppercase tracking-wider mb-3">Terkait Transaksi Jual-Beli DOMBA</span>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-slate-800">Nomor Ref: {{ $finance->transaction->reference_number }}</p>
                                        <p class="text-sm text-slate-600 mt-1">Total Transaksi: Rp {{ number_format($finance->transaction->total_price, 0, ',', '.') }}</p>
                                    </div>
                                    <a href="{{ route('transactions.show', $finance->transaction) }}" class="text-sm px-4 py-2 bg-white text-indigo-600 font-bold rounded-lg shadow-sm border border-indigo-200 hover:bg-indigo-600 hover:text-white transition-colors">
                                        Lihat Invoice
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col md:col-span-2">
                            <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">Keterangan / Rincian</span>
                            <div class="bg-white px-4 py-3 rounded-xl border border-slate-200 text-slate-700">
                                {{ $finance->description ?? 'Tidak ada rincian tambahan keterangan.' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-10 flex items-center gap-4 pt-6 border-t border-slate-100">
                        <a href="{{ route('finances.edit', $finance) }}" 
                           class="px-6 py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                            Edit Data
                        </a>
                        <form action="{{ route('finances.destroy', $finance) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan kas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-6 py-3 bg-rose-50 text-rose-600 font-semibold rounded-xl hover:bg-rose-100 transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
