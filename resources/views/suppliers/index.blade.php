<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Manajemen Supplier') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Supplier</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12  bg-[#f0f6ff] min-h-screen px-10">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">
                
                <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100">
                    <div class="p-6 lg:p-10">

                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">Daftar Supplier</h3>
                                <p class="text-sm text-slate-500 mt-1">Kelola data supplier sistem Anda.</p>
                            </div>
                            
                            <a href="{{ route('suppliers.create') }}" 
                               class="group inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Tambah Supplier
                            </a>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white">
                            <div class="overflow-visible">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Telepon</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse($suppliers as $key => $supplier)
                                        <tr class="group hover:bg-blue-50/40 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                {{ $suppliers->firstItem() + $key }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-800">
                                                {{ $supplier->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-semibold text-slate-600">
                                                {{ $supplier->phone }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-slate-600">
                                                {{ $supplier->email ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-left text-sm text-slate-600 max-w-xs truncate">
                                                {{ $supplier->address ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="relative flex justify-center" x-data="{ open: false }">
                                                    <button @click="open = !open" @click.outside="open = false"
                                                        class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all font-black tracking-widest text-lg leading-none">
                                                        <svg width="3" height="15" viewBox="0 0 3 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.5C4.47035e-08 0.671573 0.671573 0 1.5 0C2.32843 4.47035e-08 3 0.671573 3 1.5C3 2.32843 2.32843 3 1.5 3C0.671573 3 0 2.32843 0 1.5ZM0 7.5C4.47035e-08 6.67157 0.671573 6 1.5 6C2.32843 6 3 6.67157 3 7.5C3 8.32843 2.32843 9 1.5 9C0.671573 9 0 8.32843 0 7.5ZM0 13.5C4.47035e-08 12.6716 0.671573 12 1.5 12C2.32843 12 3 12.6716 3 13.5C3 14.3284 2.32843 15 1.5 15C0.671573 15 0 14.3284 0 13.5Z" fill="#555555"/>
                                                        </svg>
                                                    </button>
                                                    <div x-show="open"
                                                        x-transition:enter="transition ease-out duration-150"
                                                        x-transition:enter-start="opacity-0 scale-95"
                                                        x-transition:enter-end="opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-100"
                                                        x-transition:leave-start="opacity-100 scale-100"
                                                        x-transition:leave-end="opacity-0 scale-95"
                                                        class="absolute right-0 top-9 z-30 w-36 rounded-xl bg-white shadow-xl border border-slate-100 overflow-hidden origin-top-right"
                                                        style="display:none;">
                                                        <a href="{{ route('suppliers.show', $supplier) }}"
                                                            class="block px-4 py-2.5 text-sm  text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                            Detail
                                                        </a>
                                                        <a href="{{ route('suppliers.edit', $supplier) }}"
                                                            class="block px-4 py-2.5 text-sm  text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                            Edit 
                                                        </a>
                                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                                                            data-confirm-message="Apakah Anda yakin ingin menghapus supplier ini?">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-full text-left px-4 py-2.5 text-sm font-semibold text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bg-slate-50 p-4 rounded-full mb-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-slate-700">Tidak ada supplier ditemukan</h3>
                                                        <p class="text-sm text-slate-500 max-w-xs mx-auto mt-1">Silakan tambahkan data supplier baru.</p>
                                                        <a href="{{ route('suppliers.create') }}" class="mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                                            + Tambah Supplier
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
                            {{ $suppliers->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('suppliers.script')
</x-app-layout>