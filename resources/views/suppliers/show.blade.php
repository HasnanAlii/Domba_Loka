<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Detail Supplier') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('suppliers.index') }}" class="hover:text-blue-600 cursor-pointer transition">Supplier</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail Data</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl p-6 lg:p-8 border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all">
                        <div class="absolute right-0 top-0 h-32 w-32 bg-blue-50/50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="p-4 bg-blue-100 text-blue-600 rounded-2xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-800">{{ $supplier->name }}</h3>
                                    <p class="text-sm text-slate-500">Supplier</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex flex-col border-b border-slate-100 pb-3">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">No. Telepon</span>
                                    <span class="text-slate-700 font-medium">{{ $supplier->phone }}</span>
                                </div>
                                <div class="flex flex-col border-b border-slate-100 pb-3">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Email</span>
                                    <span class="text-slate-700 font-medium">{{ $supplier->email ?? '-' }}</span>
                                </div>
                                <div class="flex flex-col border-b border-slate-100 pb-3">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Alamat</span>
                                    <span class="text-slate-700 font-medium leading-relaxed">{{ $supplier->address ?? '-' }}</span>
                                </div>
                                <div class="flex flex-col pb-3">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Terdaftar Pada</span>
                                    <span class="text-slate-700 font-medium">{{ $supplier->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex items-center gap-3">
                                <a href="{{ route('suppliers.edit', $supplier) }}" 
                                   class="flex-1 text-center py-2.5 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                                    Edit Profil
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="flex-1" data-confirm-message="Apakah Anda yakin ingin menghapus supplier ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2.5 bg-rose-50 text-rose-600 font-semibold rounded-xl hover:bg-rose-100 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                        <div class="p-6 lg:p-8">
                            
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800">Riwayat Transaksi</h3>
                                    <p class="text-sm text-slate-500 mt-1">Daftar transaksi yang terkait dengan {{ $supplier->name }}.</p>
                                </div>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-slate-200">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Transaksi</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @if(isset($supplier->transactions) && $supplier->transactions->count() > 0)
                                            @foreach($supplier->transactions as $key => $transaction)
                                            <tr class="hover:bg-blue-50/40 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-600">
                                                    {{ $transaction->transaction_date ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') : $transaction->created_at->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-semibold text-slate-800">
                                                    <a href="{{ route('transactions.show', $transaction->id) }}" class="text-blue-600 hover:underline">
                                                        {{ $transaction->reference_number ?? '-' }}
                                                    </a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <span class="text-sm font-bold font-mono tracking-tight text-slate-700">
                                                        Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if(($transaction->sisa ?? 0) <= 0)
                                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                            Lunas
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                            Belum Lunas
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                                                    Belum ada riwayat transaksi.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('suppliers.script')
</x-app-layout>