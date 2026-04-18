<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Detail Domba') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('sheep.index') }}" class="hover:text-blue-600 cursor-pointer transition">Domba</a>
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
                        <div class="absolute right-0 top-0 h-32 w-32 bg-indigo-50/50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="p-4 bg-indigo-100 text-indigo-600 rounded-2xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-indigo-600 font-mono tracking-tight">{{ $sheep->code }}</h3>
                                    <p class="text-sm font-medium text-slate-500">{{ $sheep->sheepType->name ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex flex-col border-b border-slate-100 pb-3">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Berat Terakhir</span>
                                    <span class="text-slate-700 font-bold">{{ (float) $sheep->weight }} Kg</span>
                                </div>
                                <div class="flex flex-col border-b border-slate-100 pb-3">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kondisi</span>
                                    <span class="text-slate-700 font-medium">{{ $sheep->condition }}</span>
                                </div>
                                <div class="flex flex-col border-b border-slate-100 pb-3">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Harga Estimasi/Beli</span>
                                    <span class="text-slate-700 font-medium">Rp {{ number_format($sheep->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex flex-col pb-3">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tanggal Input</span>
                                    <span class="text-slate-700 font-medium">{{ $sheep->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex items-center gap-3">
                                <a href="{{ route('sheep.edit', $sheep) }}" 
                                   class="flex-1 text-center py-2.5 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                                    Edit Domba
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="space-y-8">
                        {{-- GROWTH RECORD --}}
                        <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                            <div class="p-6 lg:p-8">
                                
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-800">Riwayat Pertumbuhan (Growth)</h3>
                                    </div>
                                    <a href="{{ route('growths.create') }}" class="text-sm px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-100">
                                        + Tambah Record
                                    </a>
                                </div>

                                <div class="overflow-x-auto rounded-xl border border-slate-200">
                                    <table class="min-w-full divide-y divide-slate-100">
                                        <thead class="bg-slate-50/80">
                                            <tr>
                                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Berat Aktual (Kg)</th>
                                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Target (Kg)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            @if(isset($sheep->growths) && $sheep->growths->count() > 0)
                                                @foreach($sheep->growths as $key => $growth)
                                                <tr class="hover:bg-blue-50/40 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-600">
                                                        {{ \Carbon\Carbon::parse($growth->date)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $growth->weight >= $growth->target ? 'text-emerald-600' : 'text-slate-800' }}">
                                                        {{ (float) $growth->weight }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-slate-500">
                                                        {{ (float) $growth->target }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">
                                                        Belum ada riwayat pertumbuhan.
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- TRANSACTIONS RECORD --}}
                        <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                            <div class="p-6 lg:p-8">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-800">Riwayat Transaksi</h3>
                                    </div>
                                </div>

                                <div class="overflow-x-auto rounded-xl border border-slate-200">
                                    <table class="min-w-full divide-y divide-slate-100">
                                        <thead class="bg-slate-50/80">
                                            <tr>
                                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Ref</th>
                                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            @if(isset($sheep->transactions) && $sheep->transactions->count() > 0)
                                                @foreach($sheep->transactions as $key => $trx)
                                                <tr class="hover:bg-blue-50/40 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-600">
                                                        {{ $trx->created_at->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-bold text-slate-800">
                                                        {{ $trx->reference_number }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-slate-700">
                                                        Rp {{ number_format($trx->total_price ?? 0, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">
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
    </div>
</x-app-layout>
