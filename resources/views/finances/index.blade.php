<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-extrabold text-2xl leading-tight tracking-tight text-gray-800">
                {{ __('Manajemen Keuangan') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Keuangan</span>
            </nav>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 px-10 py-12">
        <div class="mx-auto space-y-8 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div
                    class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div
                        class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-bl-full bg-emerald-50 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative flex h-full flex-col justify-between">
                        <div class="mb-4 flex items-center gap-4">
                            <div class="rounded-xl bg-emerald-100 p-3 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <div class="text-sm font-bold uppercase tracking-wider text-slate-500">Total Pemasukan</div>
                        </div>
                        <div class="font-mono text-3xl font-extrabold tracking-tight text-emerald-600">
                            Rp {{ number_format($income, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div
                    class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div
                        class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-bl-full bg-rose-50 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative flex h-full flex-col justify-between">
                        <div class="mb-4 flex items-center gap-4">
                            <div class="rounded-xl bg-rose-100 p-3 text-rose-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6" />
                                </svg>
                            </div>
                            <div class="text-sm font-bold uppercase tracking-wider text-slate-500">Total Pengeluaran
                            </div>
                        </div>
                        <div class="font-mono text-3xl font-extrabold tracking-tight text-rose-600">
                            Rp {{ number_format($expense, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 p-6 text-white shadow-lg shadow-blue-500/30">
                    <div class="absolute right-0 top-0 -mr-8 -mt-8 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="relative flex h-full flex-col justify-between">
                        <div class="mb-4 flex items-center gap-4">
                            <div class="rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="text-sm font-bold uppercase tracking-wider text-blue-100">Total Dana</div>
                        </div>
                        <div class="font-mono text-3xl font-extrabold tracking-tight text-white">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                <div class="p-6 lg:p-10">
                    @if (session('success'))
                        <div id="finance-success-alert"
                            class="mb-6 flex items-center justify-between rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-emerald-700">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm font-medium">{{ session('success') }}</span>
                            </div>
                            <button type="button" data-dismiss-target="#finance-success-alert"
                                class="text-emerald-400 transition hover:text-emerald-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    <div class="mb-8 flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">Riwayat Transaksi Keuangan</h3>
                            <p class="mt-1 text-sm text-slate-500">Daftar lengkap arus kas masuk dan keluar peternakan.
                            </p>
                        </div>

                        <a href="{{ route('finances.create') }}"
                            class="group inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-blue-600/40">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tambah Kas
                        </a>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/80">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                            No</th>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Rekening</th>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Jenis</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Jumlah (Rp)</th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Keterangan</th>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">
                                            Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse ($finances as $key => $finance)
                                        <tr class="group transition-colors duration-200 hover:bg-blue-50/40">
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-center text-sm font-medium text-slate-400">
                                                {{ $finances->firstItem() + $key }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-center text-sm font-medium text-slate-600">
                                                {{ $finance->created_at->format('d/m/Y') }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-6 py-4 text-left text-sm font-semibold text-slate-700">
                                                {{ $finance->bankAccount->bank_name ?? 'Cash' }}
                                                @if ($finance->transaction)
                                                    <span
                                                        class="mt-0.5 block text-xs font-normal text-slate-400 hover:text-blue-500">
                                                        <a
                                                            href="{{ route('transactions.show', $finance->transaction) }}">{{ $finance->transaction->reference_number }}</a>
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                                @if ($finance->type === 'income')
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                        <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                        </svg>
                                                        Masuk
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                                        <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                        </svg>
                                                        Keluar
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                                <span
                                                    class="font-mono text-sm font-bold tracking-tight {{ $finance->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                    {{ $finance->type === 'income' ? '+' : '-' }} Rp
                                                    {{ number_format($finance->amount, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="max-w-xs px-6 py-4 text-sm text-slate-600">
                                                {{ $finance->description ?? '-' }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('finances.show', $finance) }}"
                                                        class="rounded-lg p-2 text-slate-400 transition-all hover:bg-blue-50 hover:text-blue-600"
                                                        title="Detail Transaksi">
                                                        <i data-feather="eye" class="h-5 w-5" aria-hidden="true"></i>
                                                    </a>

                                                    <a href="{{ route('finances.edit', $finance) }}"
                                                        class="rounded-lg p-2 text-slate-400 transition-all hover:bg-blue-50 hover:text-blue-600"
                                                        title="Edit Transaksi">
                                                        <i data-feather="edit" class="h-5 w-5"
                                                            aria-hidden="true"></i>
                                                    </a>

                                                    <form action="{{ route('finances.destroy', $finance) }}"
                                                        method="POST" class="inline"
                                                        data-confirm-message="Apakah Anda yakin ingin menghapus catatan ini?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="group rounded-lg p-2 text-slate-400 transition-all hover:bg-red-50 hover:text-red-600"
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
                                            <td colspan="7" class="px-6 py-16 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mb-4 rounded-full bg-slate-50 p-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-10 w-10 text-slate-300" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-lg font-semibold text-slate-700">Tidak ada catatan
                                                        keuangan ditemukan</h3>
                                                    <p class="mx-auto mt-1 max-w-xs text-sm text-slate-500">Silakan
                                                        tambahkan data kas masuk atau keluar baru.</p>
                                                    <a href="{{ route('finances.create') }}"
                                                        class="mt-4 text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                        + Tambah Kas
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
                        {{ $finances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('finances.script', ['page' => 'index'])
</x-app-layout>
