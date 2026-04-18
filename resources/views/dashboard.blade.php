<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-extrabold leading-tight tracking-tight text-slate-800">
                    {{ __('Dashboard Domba Loka') }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">Ringkasan operasional peternakan dan keuangan.</p>
            </div>

            <form method="GET" class="relative z-20">
                <div class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm shadow-slate-200/50">
                    <select name="month" class="w-32 rounded-xl border-slate-200 text-sm font-semibold text-slate-600 focus:border-blue-500 focus:ring-blue-500">
                        @foreach ($months as $key => $label)
                            <option value="{{ $key }}" {{ (int) $selectedMonth === (int) $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <select name="year" class="w-24 rounded-xl border-slate-200 text-sm font-semibold text-slate-600 focus:border-blue-500 focus:ring-blue-500">
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ (int) $selectedYear === (int) $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-blue-700">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto space-y-8 max-w-7xl">
            <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-xl shadow-slate-200/60">
                <h3 class="text-2xl font-extrabold text-slate-800">
                    Halo, <span class="text-blue-600">{{ Auth::user()->name }}</span>
                </h3>
                <p class="mt-2 text-slate-500">
                    Data untuk periode {{ $months[$selectedMonth] }} {{ $selectedYear }}.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Domba</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $totalSheep }}</p>
                    <p class="mt-2 text-sm text-slate-500">Rata-rata berat: {{ number_format($averageSheepWeight, 2, ',', '.') }} kg</p>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Relasi Bisnis</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $totalCustomers + $totalSuppliers }}</p>
                    <p class="mt-2 text-sm text-slate-500">{{ $totalCustomers }} pelanggan, {{ $totalSuppliers }} supplier</p>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Aktivitas Bulan Ini</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $transactionsThisMonth }}</p>
                    <p class="mt-2 text-sm text-slate-500">Transaksi, {{ $growthChecksThisMonth }} catatan pertumbuhan</p>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Saldo Bulan Ini</p>
                    <p class="mt-2 text-3xl font-extrabold {{ $balance >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        Rp {{ number_format($balance, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500">
                        Masuk Rp {{ number_format($income, 0, ',', '.') }} | Keluar Rp {{ number_format($expense, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                <div class="flex items-center justify-between border-b border-slate-100 px-8 py-6">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Aktivitas Keuangan Terbaru</h3>
                        <p class="mt-1 text-sm text-slate-500">5 data keuangan terbaru periode terpilih.</p>
                    </div>

                    <a href="{{ route('finances.index') }}" class="text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                        Lihat semua
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                                <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Jenis</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Deskripsi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Rekening</th>
                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            @forelse ($recentFinances as $finance)
                                <tr class="transition hover:bg-blue-50/40">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $finance->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($finance->type === 'income')
                                            <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">Masuk</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-200">Keluar</span>
                                        @endif
                                    </td>
                                    <td class="max-w-xs truncate px-6 py-4 text-sm text-slate-600">{{ $finance->description ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $finance->bankAccount->bank_name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right font-mono text-sm font-bold {{ $finance->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $finance->type === 'income' ? '+' : '-' }} Rp {{ number_format($finance->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada aktivitas keuangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>