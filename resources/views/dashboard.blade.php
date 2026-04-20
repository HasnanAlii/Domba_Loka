<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between py-2">
            <div>
                <h2 class="text-sm font-black text-[#03235b] uppercase tracking-[0.4em] mb-1">
                    {{ __('Panel Kontrol Utama') }}
                </h2>
                <h1 class="text-4xl font-black text-[#03235b] tracking-tighter uppercase italic leading-none">
                    Dashboard Operasional.
                </h1>
            </div>

            <form method="GET" class="relative z-20">
                <div class="flex items-center gap-2 rounded-2xl border border-slate-100 bg-white p-2 shadow-sm">
                    <div class="relative group">
                        <select name="month"
                            class="w-44 bg-slate-50 border-transparent rounded-xl text-xs font-black uppercase tracking-widest text-slate-500 focus:bg-white focus:border-[#03235b] focus:ring-0 transition-all appearance-none pr-10">
                            @foreach ($months as $key => $label)
                                <option value="{{ $key }}"
                                    {{ (int) $selectedMonth === (int) $key ? 'selected' : '' }}>
                                    {{ strtoupper($label) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-300">
                            <i data-feather="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>

                    <div class="relative group">
                        <select name="year"
                            class="w-32 bg-slate-50 border-transparent rounded-xl text-xs font-black uppercase tracking-widest text-slate-500 focus:bg-white focus:border-[#03235b] focus:ring-0 transition-all appearance-none pr-10">
                            @foreach ($years as $year)
                                <option value="{{ $year }}"
                                    {{ (int) $selectedYear === (int) $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-300">
                            <i data-feather="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>

                    <button type="submit"
                        class="rounded-xl bg-[#03235b] px-6 py-2.5 text-xs font-black tracking-widest text-white transition hover:bg-[#0c5197] shadow-lg shadow-blue-900/20 active:scale-95 uppercase">
                        Sinkronkan
                    </button>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="min-h-screen  bg-[#f0f6ff] px-6 py-12">
        <div class="mx-auto space-y-12 max-w-7xl">
            <!-- Welcome Card -->
            <div class="relative overflow-hidden rounded-[3.5rem] border border-white bg-white p-12 shadow-[0_40px_80px_-20px_rgba(3,35,91,0.04)]">
                <div class="relative z-10">
                    <h3 class="text-4xl font-black text-[#03235b] tracking-tight uppercase">
                        Halo, <span class="text-[#0c5197] italic">{{ Auth::user()->name }}</span>
                    </h3>
                    <p class="mt-3 text-sm font-black text-slate-400 uppercase tracking-[0.2em]">
                        Ringkasan Performa: {{ $months[$selectedMonth] }} {{ $selectedYear }}.
                    </p>
                </div>
                <!-- Subtle Gradient Pattern -->
                <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-50/20 to-transparent pointer-events-none"></div>
            </div>

            <!-- Operational Stats Grid -->
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Stat Item 1 -->
                <div class="group relative overflow-hidden rounded-[2.5rem] border border-white bg-white p-10 shadow-sm transition-all hover:shadow-[0_30px_60px_-15px_rgba(3,35,91,0.08)] hover:-translate-y-1">
                    <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-400 group-hover:text-[#0c5197] transition-colors mb-6">Inventarisasi Ternak</p>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Total Populasi</p>
                            <p class="text-5xl font-black text-[#03235b] tracking-tighter leading-none">{{ $totalSheep }}</p>
                        </div>
                        <div class="p-5 bg-blue-50/50 backdrop-blur-sm text-[#0c5197] rounded-[1.8rem] shadow-inner border border-white/20">
                            <i data-feather="box" class="w-7 h-7"></i>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-slate-100/50 flex items-center justify-between">
                         <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Rata-rata Berat</p>
                         <p class="text-sm font-black text-[#0c5197] uppercase tracking-tight">{{ number_format($averageSheepWeight, 1, ',', '.') }} KG</p>
                    </div>
                </div>

                <!-- Stat Item 2 -->
                <div class="group relative overflow-hidden rounded-[2.5rem] border border-white bg-white p-10 shadow-sm transition-all hover:shadow-[0_30px_60px_-15px_rgba(3,35,91,0.08)] hover:-translate-y-1">
                    <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-400 group-hover:text-[#0c5197] transition-colors mb-6">Relasi Strategis</p>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Jaringan Bisnis</p>
                            <p class="text-5xl font-black text-[#03235b] tracking-tighter leading-none">{{ $totalCustomers + $totalSuppliers }}</p>
                        </div>
                        <div class="p-5 bg-emerald-50/50 backdrop-blur-sm text-emerald-600 rounded-[1.8rem] shadow-inner border border-white/20">
                            <i data-feather="users" class="w-7 h-7"></i>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-slate-100/50 flex items-center justify-between gap-6">
                         <div class="text-left">
                            <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Pelanggan</p>
                            <p class="text-lg font-black text-emerald-600 uppercase tracking-tight">{{ $totalCustomers }}</p>
                         </div>
                         <div class="w-px h-10 bg-slate-100/50"></div>
                         <div class="text-left">
                            <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Supplier</p>
                            <p class="text-lg font-black text-emerald-600 uppercase tracking-tight">{{ $totalSuppliers }}</p>
                         </div>
                    </div>
                </div>

                <!-- Stat Item 3 -->
                <div class="group relative overflow-hidden rounded-[2.5rem] border border-white bg-white p-10 shadow-sm transition-all hover:shadow-[0_30px_60px_-15px_rgba(3,35,91,0.08)] hover:-translate-y-1">
                    <p class="text-sm font-black uppercase tracking-[0.2em] text-slate-400 group-hover:text-[#0c5197] transition-colors mb-6">Monitoring Pertumbuhan</p>
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Audit Berjalan</p>
                            <p class="text-5xl font-black text-[#03235b] tracking-tighter leading-none">{{ $transactionsThisMonth }}</p>
                        </div>
                        <div class="p-5 bg-amber-50/50 backdrop-blur-sm text-amber-600 rounded-[1.8rem] shadow-inner border border-white/20">
                            <i data-feather="activity" class="w-7 h-7"></i>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-slate-100/50 flex items-center justify-between">
                         <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Log Terdata</p>
                         <p class="text-sm font-black text-amber-600 uppercase tracking-tight">{{ $growthChecksThisMonth }} Audit</p>
                    </div>
                </div>
            </div>

            <!-- Standalone Financial Section -->
            <div class="group relative overflow-hidden rounded-[3.5rem] border border-white bg-white p-12 shadow-xl shadow-blue-900/5 transition-all hover:shadow-2xl hover:-translate-y-1 flex flex-col xl:flex-row xl:items-center justify-between gap-12">
                <div class="absolute top-0 right-0 w-1/4 h-full bg-slate-50/20 -skew-x-12 translate-x-1/2 pointer-events-none"></div>
                
                <div class="relative z-10 flex-1 space-y-8">
                    <div class="space-y-1">
                        <h4 class="text-[11px] font-black text-[#0c5197] uppercase tracking-[0.4em]">Audit Finansial</h4>
                        <h3 class="text-3xl font-black text-[#03235b] uppercase tracking-tighter">Efisiensi Keuangan.</h3>
                    </div>
                    
                    <div class="flex items-center gap-10">
                        <div class="space-y-1">
                            <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Profitabilitas Periode Ini</p>
                            <p class="text-6xl font-black tracking-tighter leading-tight {{ $balance >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $balance >= 0 ? '+' : '-' }}Rp{{ abs($balance) >= 1000000 ? number_format(abs($balance) / 1000000, 1, ',', '.') . ' Jt' : number_format(abs($balance), 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="p-7 {{ $balance >= 0 ? 'bg-emerald-50/50 text-emerald-500' : 'bg-rose-50/50 text-rose-500' }} rounded-[2.5rem] shadow-inner border border-white/20 backdrop-blur-sm">
                            <i data-feather="{{ $balance >= 0 ? 'trending-up' : 'trending-down' }}" class="w-12 h-12"></i>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 w-full xl:w-auto flex flex-col sm:flex-row gap-10 bg-white/90 p-12 rounded-[3.5rem] border border-white backdrop-blur-md">
                    <div class="space-y-5 min-w-[200px]">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Inflow</p>
                        </div>
                        <p class="text-3xl font-black text-emerald-500 tracking-tight">
                            Rp{{ $income >= 1000000 ? number_format($income / 1000000, 1, ',', '.') . ' Jt' : number_format($income, 0, ',', '.') }}
                        </p>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="hidden sm:block w-px h-28 bg-slate-200/50"></div>

                    <div class="space-y-5 min-w-[200px]">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Total Outflow</p>
                        </div>
                        <p class="text-3xl font-black text-rose-400 tracking-tight">
                            Rp{{ $expense >= 1000000 ? number_format($expense / 1000000, 1, ',', '.') . ' Jt' : number_format($expense, 0, ',', '.') }}
                        </p>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-rose-400" style="width: {{ $income > 0 ? (min(($expense / $income) * 100, 100)) : 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Deep Intelligence Section -->
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
                <!-- Data Visualizations -->
                <div class="space-y-10">
                    <!-- Penjualan Card -->
                    <div class="rounded-[3rem] border border-white bg-white p-10 shadow-sm overflow-hidden relative">
                         <div class="flex items-center justify-between mb-8">
                            <h4 class="text-base font-black text-[#03235b] uppercase tracking-wider">Tren Penjualan</h4>
                            <div class="flex items-center gap-2 rounded-xl bg-slate-100/50 backdrop-blur-sm px-4 py-2 text-[10px] font-black text-slate-400 border border-white/20">
                                {{ $months[$selectedMonth] }} {{ $selectedYear }} <i data-feather="calendar" class="w-3 h-3 ml-1"></i>
                            </div>
                         </div>
                         <div class="space-y-2 mb-10">
                            <div class="flex items-center gap-3">
                                <p class="text-4xl font-black text-[#03235b]">Rp{{ number_format($income, 0, ',', '.') }}</p>
                                <span class="rounded-full {{ $incomeGrowth >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }} px-3 py-1 text-[10px] font-black text-white uppercase italic">
                                    {{ $incomeGrowth >= 0 ? '+' : '' }}{{ number_format($incomeGrowth, 2) }}%
                                </span>
                            </div>
                            <p class="text-xs font-black text-slate-300 uppercase italic">
                                {{ $incomeGrowth >= 0 ? 'Pertumbuhan positif' : 'Penurunan performa' }} dibandingkan periode lalu
                            </p>
                         </div>
                         <div class="h-40 w-full flex items-end gap-1 px-2 border-b-2 border-slate-100/50">
                            @foreach($chartIncome as $day => $percentage)
                                <div class="flex-1 {{ $percentage > 0 ? 'bg-emerald-500/80' : 'bg-slate-100/30' }} rounded-t-sm transition-all hover:bg-emerald-600 group/bar relative" style="height: {{ max($percentage, 5) }}%">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#03235b] text-white text-[8px] font-black px-1.5 py-0.5 rounded opacity-0 group-hover/bar:opacity-100 transition-opacity">
                                        Tgl {{ $day }}
                                    </div>
                                </div>
                            @endforeach
                         </div>
                    </div>

                    <!-- Pengeluaran Card -->
                    <div class="rounded-[3rem] border border-white bg-white p-10 shadow-sm overflow-hidden relative">
                         <div class="flex items-center justify-between mb-8">
                            <h4 class="text-base font-black text-[#03235b] uppercase tracking-wider">Arus Pengeluaran</h4>
                            <div class="flex items-center gap-2 rounded-xl bg-slate-100/50 backdrop-blur-sm px-4 py-2 text-[10px] font-black text-slate-400 border border-white/20">
                                {{ $months[$selectedMonth] }} {{ $selectedYear }} <i data-feather="calendar" class="w-3 h-3 ml-1"></i>
                            </div>
                         </div>
                         <div class="space-y-2 mb-10">
                            <div class="flex items-center gap-3">
                                <p class="text-4xl font-black text-[#03235b]">Rp{{ number_format($expense, 0, ',', '.') }}</p>
                                <span class="rounded-full {{ $expenseGrowth <= 0 ? 'bg-emerald-500' : 'bg-rose-500' }} px-3 py-1 text-[10px] font-black text-white uppercase italic">
                                    {{ $expenseGrowth >= 0 ? '+' : '' }}{{ number_format($expenseGrowth, 2) }}%
                                </span>
                            </div>
                            <p class="text-xs font-black text-slate-300 uppercase italic">
                                {{ $expenseGrowth <= 0 ? 'Efisiensi biaya terjaga' : 'Peningkatan beban biaya' }} periode ini
                            </p>
                         </div>
                         <div class="h-40 w-full flex items-end gap-1 px-2 border-b-2 border-slate-100/50">
                            @foreach($chartExpense as $day => $percentage)
                                <div class="flex-1 {{ $percentage > 0 ? 'bg-rose-400/80' : 'bg-slate-100/30' }} rounded-t-sm transition-all hover:bg-rose-500 group/bar relative" style="height: {{ max($percentage, 5) }}%">
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#03235b] text-white text-[8px] font-black px-1.5 py-0.5 rounded opacity-0 group-hover/bar:opacity-100 transition-opacity">
                                        Tgl {{ $day }}
                                    </div>
                                </div>
                            @endforeach
                         </div>
                    </div>
                </div>

                <!-- Strategic Lists -->
                <div class="space-y-10">
                    <!-- Produk Terlaris -->
                    <div class="rounded-[3rem] border border-white bg-white p-10 shadow-sm">
                        <div class="flex items-center gap-5 border-b border-slate-100/50 pb-8 mb-8">
                            <div class="p-4 bg-slate-100/50 backdrop-blur-sm text-slate-400 rounded-2xl border border-white/20">
                                <i data-feather="shopping-bag" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-black text-[#03235b] uppercase tracking-tight">Katalog Teraktif</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Jenis domba paling populer saat ini</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                             @forelse($topSheepTypes as $type)
                                <div class="flex items-center justify-between p-7 hover:bg-slate-100 transition-all rounded-[2rem] group border border-transparent hover:border-white/80 hover:shadow-sm">
                                    <p class="text-sm font-black text-[#03235b] uppercase italic group-hover:translate-x-1 transition-transform tracking-tight">{{ $type->name }}</p>
                                    <div class="flex items-center gap-3">
                                        <p class="text-base font-black text-[#0c5197] leading-none">{{ $type->sheep_count }}</p>
                                        <span class="text-[9px] font-black text-slate-300 uppercase">Unit</span>
                                    </div>
                                </div>
                             @empty
                                <p class="text-center py-10 text-xs font-black text-slate-200 uppercase tracking-widest italic">Belum ada data katalog.</p>
                             @endforelse
                        </div>
                    </div>

                    <!-- Stakeholders Analysis -->
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:sm:grid-cols-1 xl:sm:grid-cols-1">
                        <!-- Pelanggan Terloyal -->
                        <div class="rounded-[3rem] border border-white bg-white p-10 shadow-sm">
                            <div class="flex items-center gap-5 mb-8">
                                <div class="p-4 bg-blue-50/50 backdrop-blur-sm text-blue-500 rounded-2xl shadow-inner border border-white/20">
                                    <i data-feather="users" class="w-6 h-6"></i>
                                </div>
                                <h4 class="text-base font-black text-[#03235b] uppercase tracking-tight">Pelanggan Teroyal</h4>
                            </div>
                            <div class="space-y-4">
                                 @forelse($loyalCustomers as $customer)
                                    <div class="flex items-center justify-between py-4 border-b border-slate-100/50 last:border-0 group">
                                        <p class="text-sm font-black text-[#03235b] uppercase italic group-hover:text-[#0c5197] transition-colors">{{ $customer->name }}</p>
                                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">{{ $customer->transactions_count }} Transaksi</p>
                                    </div>
                                 @empty
                                    <p class="text-center py-6 text-xs font-black text-slate-200 uppercase tracking-widest italic tracking-widest">Belum ada data.</p>
                                 @endforelse
                            </div>
                        </div>

                        <!-- Pemasok Tertinggi -->
                        <div class="rounded-[3rem] border border-white bg-white p-10 shadow-sm">
                            <div class="flex items-center gap-5 mb-8">
                                <div class="p-4 bg-emerald-50/50 backdrop-blur-sm text-emerald-500 rounded-2xl shadow-inner border border-white/20">
                                    <i data-feather="truck" class="w-6 h-6"></i>
                                </div>
                                <h4 class="text-base font-black text-[#03235b] uppercase tracking-tight">Pemasok Tertinggi </h4>
                            </div>
                            <div class="space-y-4">
                                 @forelse($topSuppliers as $supplier)
                                    <div class="flex items-center justify-between py-4 border-b border-slate-100/50 last:border-0 group">
                                        <p class="text-sm font-black text-[#03235b] uppercase italic group-hover:text-emerald-600 transition-colors">{{ $supplier->name }}</p>
                                        <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest">{{ $supplier->transactions_count }} Transaksi</p>
                                    </div>
                                 @empty
                                    <p class="text-center py-6 text-xs font-black text-slate-200 uppercase tracking-widest italic tracking-widest">Belum ada data.</p>
                                 @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
