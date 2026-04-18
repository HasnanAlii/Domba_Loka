import os

def create_view(folder, filename, content):
    path = f"resources/views/{folder}/{filename}"
    with open(path, "w") as f:
        f.write(content)

# Data for suppliers
suppliers_index = """<x-app-layout>
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

    <div class="py-12 bg-slate-50 min-h-screen px-10">
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

                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                            <div class="overflow-x-auto">
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
                                                <div class="flex justify-center items-center gap-2">
                                                    <a href="{{ route('suppliers.show', $supplier) }}" 
                                                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                       title="Detail Supplier">
                                                       <i data-feather="eye" class="h-5 w-5" aria-hidden="true"></i>
                                                    </a>
                                                    
                                                    <a href="{{ route('suppliers.edit', $supplier) }}" 
                                                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                       title="Edit Supplier">
                                                        <i data-feather="edit" class="h-5 w-5" aria-hidden="true"></i>
                                                    </a>

                                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
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
</x-app-layout>"""

create_view('suppliers', 'index.blade.php', suppliers_index)

suppliers_create = """<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Tambah Supplier') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('suppliers.index') }}" class="hover:text-blue-600 cursor-pointer transition">Supplier</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Tambah Data</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8 max-w-4xl">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                <div class="p-6 lg:p-10">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-slate-800">Form Tambah Supplier</h3>
                        <p class="text-sm text-slate-500 mt-1">Silakan lengkapi formulir di bawah ini untuk menambahkan supplier baru.</p>
                    </div>

                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="col-span-1 md:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600"
                                    placeholder="Masukkan nama supplier">
                                @error('name')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">No. Telepon <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600"
                                    placeholder="Contoh: 081234567890">
                                @error('phone')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600"
                                    placeholder="Contoh: email@domain.com">
                                @error('email')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label for="address" class="block text-sm font-semibold text-slate-700 mb-2">Alamat</label>
                                <textarea name="address" id="address" rows="4"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600"
                                    placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                            <a href="{{ route('suppliers.index') }}" 
                               class="px-6 py-3 text-slate-500 hover:text-slate-700 font-medium transition-colors">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>"""

create_view('suppliers', 'create.blade.php', suppliers_create)

suppliers_edit = """<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Edit Supplier') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('suppliers.index') }}" class="hover:text-blue-600 cursor-pointer transition">Supplier</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Edit Data</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8 max-w-4xl">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                <div class="p-6 lg:p-10">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-slate-800">Form Edit Supplier</h3>
                        <p class="text-sm text-slate-500 mt-1">Perbarui informasi data supplier.</p>
                    </div>

                    <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="col-span-1 md:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600"
                                    placeholder="Masukkan nama supplier">
                                @error('name')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">No. Telepon <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $supplier->phone) }}" required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600"
                                    placeholder="Contoh: 081234567890">
                                @error('phone')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600"
                                    placeholder="Contoh: email@domain.com">
                                @error('email')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label for="address" class="block text-sm font-semibold text-slate-700 mb-2">Alamat</label>
                                <textarea name="address" id="address" rows="4"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600"
                                    placeholder="Masukkan alamat lengkap">{{ old('address', $supplier->address) }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                            <a href="{{ route('suppliers.index') }}" 
                               class="px-6 py-3 text-slate-500 hover:text-slate-700 font-medium transition-colors">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                Perbarui Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>"""

create_view('suppliers', 'edit.blade.php', suppliers_edit)

suppliers_show = """<x-app-layout>
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
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
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
                                                    {{ $transaction->created_at->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-semibold text-slate-800">
                                                    {{ $transaction->reference_number ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <span class="text-sm font-bold font-mono tracking-tight text-slate-700">
                                                        Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}
                                                    </span>
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
</x-app-layout>"""

create_view('suppliers', 'show.blade.php', suppliers_show)
