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
                    <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100 group hover:shadow-2xl transition-all duration-500">
                        {{-- Photo Section --}}
                        <div class="relative h-64 w-full bg-slate-100 overflow-hidden">
                            @if($sheep->photo)
                                <img src="{{ asset('storage/' . $sheep->photo) }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Foto Domba">
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-slate-300">
                                    <svg class="w-16 h-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[10px] uppercase font-black tracking-widest">Tidak ada foto</span>
                                </div>
                            @endif

                            {{-- Status Badge Overlay --}}
                            @php
                                $statusColors = [
                                    'tersedia' => 'bg-emerald-500',
                                    'terjual' => 'bg-blue-500',
                                    'sakit' => 'bg-amber-500',
                                    'mati' => 'bg-rose-500',
                                    'hilang' => 'bg-slate-500'
                                ];
                                $color = $statusColors[$sheep->status] ?? 'bg-slate-400';
                            @endphp
                            <div class="absolute top-4 right-4">
                                <span class="{{ $color }} text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-lg">
                                    {{ $sheep->status }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6 lg:p-8">
                            <div class="flex flex-col gap-1 mb-8">
                                <h3 class="text-2xl font-black text-slate-800 font-mono tracking-tight">{{ $sheep->code }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $color }}"></span>
                                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $sheep->sheepType->name ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-5">
                                <div class="flex justify-between items-center border-b border-slate-50 pb-4">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Berat Terakhir</span>
                                    <span class="text-slate-800 font-extrabold text-lg">{{ (float) $sheep->weight }} <span class="text-xs font-bold text-slate-400">KG</span></span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-50 pb-4">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Umur</span>
                                    <span class="text-slate-800 font-extrabold text-lg">{{ $sheep->age }} <span class="text-xs font-bold text-slate-400">BLN</span></span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-50 pb-4">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kondisi</span>
                                    @php
                                        $conditionColors = [
                                            'Sehat' => 'text-emerald-600 bg-emerald-50',
                                            'Sakit Ringan' => 'text-amber-600 bg-amber-50',
                                            'Sakit Parah' => 'text-rose-600 bg-rose-50',
                                            'Cacat' => 'text-slate-600 bg-slate-50'
                                        ];
                                        $cColor = $conditionColors[$sheep->condition] ?? 'text-slate-600 bg-slate-50';
                                    @endphp
                                    <span class="{{ $cColor }} px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider">{{ $sheep->condition }}</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-50 pb-4">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Harga Dasar/Beli</span>
                                    <span class="text-blue-600 font-black tracking-tight">Rp {{ number_format($sheep->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tanggal Masuk</span>
                                    <span class="text-slate-600 font-bold text-xs">{{ $sheep->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-10 pt-6 border-t border-slate-100">
                                <a href="{{ route('sheep.edit', $sheep) }}" 
                                   class="block w-full text-center py-4 bg-slate-900 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-600 shadow-xl shadow-slate-200 transition-all duration-300 transform hover:-translate-y-1">
                                    Edit Profil Domba
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Photos Gallery --}}
                    @if($sheep->photos->count() > 0)
                        <div class="mt-8 bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100 p-6 lg:p-8">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Galeri Foto Tambahan</h3>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($sheep->photos as $p)
                                    <div class="relative aspect-square rounded-[1.5rem] overflow-hidden border border-slate-100 group/gallery">
                                        <img src="{{ asset('storage/' . $p->path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover/gallery:scale-125">
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/gallery:opacity-100 transition-opacity flex items-center justify-center">
                                            <i data-feather="zoom-in" class="text-white w-5 h-5"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
