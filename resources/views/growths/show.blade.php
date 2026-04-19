<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Detail Pertumbuhan') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('growths.index') }}" class="hover:text-blue-600 cursor-pointer transition">Pertumbuhan</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail Data</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12  bg-[#f0f6ff] min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8 max-w-4xl">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl p-6 lg:p-10 border border-slate-100 relative overflow-hidden group">
                <div class="absolute right-0 top-0 h-40 w-40 bg-emerald-50/50 rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-5 mb-8 border-b border-slate-100 pb-6">
                        <div class="p-4 bg-emerald-100 text-emerald-600 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800">{{ $growth->sheep->code ?? '-' }}</h3>
                            <p class="text-sm text-slate-500 font-medium">Domba Jenis: <span class="text-slate-700">{{ $growth->sheep->sheepType->name ?? '-' }}</span></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">Tanggal Penimbangan</span>
                            <span class="text-slate-700 font-medium text-lg">{{ \Carbon\Carbon::parse($growth->date)->format('l, d F Y') }}</span>
                        </div>
                        
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-2">Status Target</span>
                            <div>
                                @if($growth->weight >= $growth->target)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Target Tercapai 🎉</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-sm font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">Target Belum Tercapai</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Berat Aktual</span>
                            <span class="text-4xl font-extrabold text-blue-600 font-mono tracking-tighter">{{ (float) $growth->weight }} <span class="text-xl text-blue-400 font-medium">Kg</span></span>
                        </div>

                        <div class="flex flex-col bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Target Berat</span>
                            <span class="text-4xl font-extrabold text-slate-700 font-mono tracking-tighter">{{ (float) $growth->target }} <span class="text-xl text-slate-400 font-medium">Kg</span></span>
                        </div>
                    </div>
                    
                    <div class="mt-10 flex items-center gap-4 pt-6 border-t border-slate-100">
                        <a href="{{ route('growths.edit', $growth) }}" 
                           class="px-6 py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                            Edit Data
                        </a>
                        <form action="{{ route('growths.destroy', $growth) }}" method="POST" data-confirm-message="Apakah Anda yakin ingin menghapus catatan ini?">
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

    @include('growths.script')
</x-app-layout>
