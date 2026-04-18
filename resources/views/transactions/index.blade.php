<x-app-layout>
    @php
        $isPenjualan = $selectedType === 'penjualan';
        $isPembelian = $selectedType === 'pembelian';
        $pageTitle = $isPenjualan
            ? 'Manajemen Penjualan'
            : ($isPembelian
                ? 'Manajemen Pembelian'
                : 'Manajemen Transaksi');
        $sectionTitle = $isPenjualan ? 'Daftar Penjualan' : ($isPembelian ? 'Daftar Pembelian' : 'Daftar Transaksi');
        $sectionDescription = $isPenjualan
            ? 'Kelola transaksi penjualan ke customer.'
            : ($isPembelian
                ? 'Kelola transaksi pembelian dari supplier.'
                : 'Kelola data jual beli dan rekap transaksi.');
        $createLabel = $isPenjualan ? 'Tambah Penjualan' : ($isPembelian ? 'Tambah Pembelian' : 'Tambah Transaksi');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __($pageTitle) }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Transaksi</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen px-10">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                    <div class="p-6 lg:p-10">

                        @if (session('success'))
                            <div x-data="{ show: true }" x-show="show"
                                class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-medium text-sm">{{ session('success') }}</span>
                                </div>
                                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif

                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">{{ $sectionTitle }}</h3>
                                <p class="text-sm text-slate-500 mt-1">{{ $sectionDescription }}</p>
                            </div>

                            <a href="{{ route('transactions.create', $selectedType ? ['type' => $selectedType] : []) }}"
                                class="group inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $createLabel }}
                            </a>
                        </div>

                        <div class="mb-6 rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                            <form action="{{ route('transactions.index') }}" method="GET"
                                class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
                                @if ($selectedType)
                                    <input type="hidden" name="type" value="{{ $selectedType }}">
                                @endif

                                <div>
                                    <label for="filter_date"
                                        class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal</label>
                                    <input type="date" id="filter_date" name="date" value="{{ $filters['date'] }}"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                </div>

                                <div>
                                    <label for="filter_reference_number"
                                        class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">No
                                        Ref</label>
                                    <input type="text" id="filter_reference_number" name="reference_number"
                                        value="{{ $filters['reference_number'] }}" placeholder="Cari no referensi"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                </div>
                                <div>
                                    <label for="filter_sheep_id"
                                        class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Domba</label>
                                    <select id="filter_sheep_id" name="sheep_id"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                        <option value="">Semua Domba</option>
                                        @foreach ($sheepOptions as $sheep)
                                            <option value="{{ $sheep->id }}" @selected((string) $filters['sheep_id'] === (string) $sheep->id)>
                                                {{ $sheep->code }} ({{ $sheep->sheepType->name ?? '-' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="filter_total_price"
                                        class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Total
                                        Harga</label>
                                    <input type="number" id="filter_total_price" name="total_price" min="0"
                                        step="0.01" value="{{ $filters['total_price'] }}"
                                        placeholder="Masukkan Total Harga"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                </div>

                                @if ($isPenjualan)
                                    <div>
                                        <label for="filter_customer_id"
                                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Pelanggan</label>
                                        <select id="filter_customer_id" name="customer_id"
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            <option value="">Semua Pelanggan</option>
                                            @foreach ($customerOptions as $customer)
                                                <option value="{{ $customer->id }}" @selected((string) $filters['customer_id'] === (string) $customer->id)>
                                                    {{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif ($isPembelian)
                                    <div>
                                        <label for="filter_supplier_id"
                                            class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Supplier</label>
                                        <select id="filter_supplier_id" name="supplier_id"
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            <option value="">Semua Supplier</option>
                                            @foreach ($supplierOptions as $supplier)
                                                <option value="{{ $supplier->id }}" @selected((string) $filters['supplier_id'] === (string) $supplier->id)>
                                                    {{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="flex flex-col justify-end">
                                    <span
                                        class="mb-2 block text-xs font-bold uppercase tracking-wider text-transparent select-none">Aksi</span>
                                    <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700">Terapkan</button>
                                </div>
                            </form>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                No</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                No. Ref</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Jenis</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Pihak (C/S)</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Domba</th>
                                            <th
                                                class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Total Harga</th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse($transactions as $key => $t)
                                            <tr class="group hover:bg-blue-50/40 transition-colors duration-200">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                    {{ $transactions->firstItem() + $key }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-600">
                                                    {{ $t->created_at->format('d/m/Y') }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-bold text-blue-600 font-mono tracking-wider">
                                                    {{ $t->reference_number }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-800">
                                                    @if ($t->type === 'penjualan')
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Penjualan</span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/20">Pembelian</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-800">
                                                    @if ($t->customer_id)
                                                        <span
                                                            class="text-emerald-700 bg-emerald-50 px-2 py-1 rounded-md text-xs mr-1">C</span>
                                                        {{ $t->customer->name ?? '-' }}
                                                    @elseif($t->supplier_id)
                                                        <span
                                                            class="text-rose-700 bg-rose-50 px-2 py-1 rounded-md text-xs mr-1">S</span>
                                                        {{ $t->supplier->name ?? '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-semibold text-slate-700">
                                                    @if ($t->details->count() > 1)
                                                        <span
                                                            class="text-xs font-normal text-slate-500">{{ $t->details->count() }}
                                                            Item / Domba</span>
                                                    @else
                                                        {{ $t->details->first()?->sheep->code ?? '-' }} <span
                                                            class="text-xs font-normal text-slate-500">(x{{ $t->details->first()?->quantity ?? 1 }})</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-slate-800">
                                                    Rp {{ number_format($t->total_price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="flex justify-center items-center gap-2">
                                                        <a href="{{ route('transactions.show', $t) }}"
                                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                            title="Detail Transaksi">
                                                            <i data-feather="eye" class="h-5 w-5"
                                                                aria-hidden="true"></i>
                                                        </a>

                                                        <a href="{{ route('transactions.edit', ['transaction' => $t, 'type' => $selectedType ?? $t->type]) }}"
                                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                                            title="Edit Transaksi">
                                                            <i data-feather="edit" class="h-5 w-5"
                                                                aria-hidden="true"></i>
                                                        </a>

                                                        <form action="{{ route('transactions.destroy', $t) }}"
                                                            method="POST" class="inline"
                                                            data-confirm-message="Apakah Anda yakin ingin menghapus transaksi ini?">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="group p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                                title="Hapus Data">
                                                                <i data-feather="trash-2" class="h-5 w-5"
                                                                    aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bg-slate-50 p-4 rounded-full mb-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-10 w-10 text-slate-300" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-slate-700">Tidak ada
                                                            transaksi ditemukan</h3>
                                                        <p class="text-sm text-slate-500 max-w-xs mx-auto mt-1">Silakan
                                                            tambahkan data transaksi jual atau beli domba.</p>
                                                        <a href="{{ route('transactions.create', $selectedType ? ['type' => $selectedType] : []) }}"
                                                            class="mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                                            + {{ $createLabel }}
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
                            {{ $transactions->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('transactions.script')
</x-app-layout>
