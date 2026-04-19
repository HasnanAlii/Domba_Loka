<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Manajemen Rekening Bank') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Rekening Bank</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12  bg-[#f0f6ff] min-h-screen px-10">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">
                
                <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                    <div class="p-6 lg:p-10">
                        
                        @if(session('success'))
                            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-medium text-sm">{{ session('success') }}</span>
                                </div>
                                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        @endif

                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">Daftar Rekening Bank</h3>
                                <p class="text-sm text-slate-500 mt-1">Kelola data rekening bank sistem Anda.</p>
                            </div>
                            
                            <a href="{{ route('bank-accounts.create') }}" 
                               class="group inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Tambah Rekening
                            </a>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Bank</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Rekening</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Akun</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Saldo</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse($bankAccounts as $key => $bankAccount)
                                        <tr class="group hover:bg-blue-50/40 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                {{ $bankAccounts->firstItem() + $key }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-800">
                                                {{ $bankAccount->bank_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-bold text-slate-600 font-mono tracking-wider">
                                                {{ $bankAccount->account_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-slate-600">
                                                {{ $bankAccount->account_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-slate-700">
                                                Rp {{ number_format($bankAccount->saldo, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex justify-center items-center gap-2">
                                                    <a href="{{ route('bank-accounts.show', $bankAccount) }}" 
                                                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                       title="Detail Rekening">
                                                       <i data-feather="eye" class="h-5 w-5" aria-hidden="true"></i>
                                                    </a>
                                                    
                                                    <a href="{{ route('bank-accounts.edit', $bankAccount) }}" 
                                                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                       title="Edit Rekening">
                                                        <i data-feather="edit" class="h-5 w-5" aria-hidden="true"></i>
                                                    </a>

                                                    <form action="{{ route('bank-accounts.destroy', $bankAccount) }}" method="POST" class="inline" data-confirm-message="Apakah Anda yakin ingin menghapus rekening ini?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="group p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                                title="Hapus Data">
                                                                <i data-feather="trash-2" class="h-5 w-5" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bg-slate-50 p-4 rounded-full mb-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-slate-700">Tidak ada rekening bank ditemukan</h3>
                                                        <p class="text-sm text-slate-500 max-w-xs mx-auto mt-1">Silakan tambahkan data rekening bank baru.</p>
                                                        <a href="{{ route('bank-accounts.create') }}" class="mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                                            + Tambah Rekening
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6">
                            {{ $bankAccounts->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('bank-accounts.script')
</x-app-layout>
