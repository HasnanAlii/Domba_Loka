<nav
    class="fixed inset-y-0 left-0 w-[270px] bg-gradient-to-b from-[#03235b] via-[#0c5197] to-[#1c88da] shadow-2xl z-50 font-sans text-white overflow-y-auto flex flex-col">

    {{-- BAGIAN ATAS --}}
    <div class="flex-1 flex flex-col">

        {{-- Header / Logo --}}
        <div class="px-6 py-7 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="bg-white p-2 rounded-lg flex items-center justify-center">
                    <x-application-logo class="h-6 w-6 text-[#03235b]" />
                </div>
                <span class="text-xl font-extrabold text-white tracking-tight">Domba Loka</span>
            </div>

            {{-- Tombol Collapse --}}
            <button @click="window.innerWidth < 768 ? showSidebar = false : sidebarCollapsed = !sidebarCollapsed"
                class="bg-[#f0f4f8] hover:bg-white text-[#03235b] rounded-lg p-1.5 shadow-sm transition-all ml-1 w-8 h-8 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 stroke-[3px] transition-transform duration-300 collapse-icon" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>

        {{-- Menu Utama --}}
        <div class="px-5 pb-6 flex-1">

            {{-- RINGKASAN --}}
            <div class="mt-2">
                <p class="px-2 mb-2 text-xs font-extrabold text-[#74b3ea] uppercase tracking-widest">Ringkasan</p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" title="Halaman Utama"
                        class="relative flex items-center gap-3.5 px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group
                       {{ request()->routeIs('dashboard')
                           ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm'
                           : 'text-white hover:bg-white/5' }}">
                        @if (request()->routeIs('dashboard'))
                            <div
                                class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md">
                            </div>
                        @endif
                        <i data-feather="grid"
                            class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            {{-- LAPORAN --}}
            <div class="mt-7">
                <p class="px-2 mb-2 text-xs font-extrabold text-[#74b3ea] uppercase tracking-widest">Laporan</p>
                <div class="space-y-1">
                    @php
                        $isReportActive = request()->routeIs('reports.*');
                    @endphp
                    <div x-data="{ open: {{ $isReportActive ? 'true' : 'false' }} }">
                        <button @click="open = !open" title="Menu Laporan"
                            class="w-full relative flex items-center justify-between px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ $isReportActive ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if ($isReportActive)
                                <div
                                    class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md">
                                </div>
                            @endif
                            <div class="flex items-center gap-3.5">
                                <i data-feather="bar-chart-2"
                                    class="w-5 h-5 {{ $isReportActive ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Laporan</span>
                            </div>
                            <svg :class="{ 'rotate-180': open }"
                                class="w-4 h-4 transition-transform duration-200 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <ul x-show="open" x-collapse style="{{ $isReportActive ? '' : 'display: none;' }}"
                            class="mt-1.5 space-y-1.5 pl-[52px] pr-2">
                            @php
                                $reportLinks = [
                                    'laba-rugi' => 'Laba Rugi',
                                    'pertumbuhan-domba' => 'Pertumbuhan Domba',
                                    'keuangan' => 'Keuangan',
                                    'penjualan' => 'Penjualan',
                                    'pembelian' => 'Pembelian',
                                ];
                            @endphp
                            @foreach ($reportLinks as $route => $label)
                                <li>
                                    <a href="{{ route('reports.' . $route) }}" title="{{ $label }}"
                                        class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('reports.' . $route) ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">
                                        {{ $label }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- TRANSAKSI --}}
            <div class="mt-7">
                <p class="px-2 mb-2 text-xs font-extrabold text-[#74b3ea] uppercase tracking-widest">Transaksi</p>
                <div class="space-y-1">

                    {{-- Penjualan --}}
                    @php $isPenjualan = request()->routeIs('transactions.*') && request()->query('type') == 'penjualan'; @endphp
                    <div x-data="{ open: {{ $isPenjualan ? 'true' : 'false' }} }">
                        <button @click="open = !open" title="Menu Penjualan"
                            class="w-full relative flex items-center justify-between px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ $isPenjualan ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if ($isPenjualan)
                                <div
                                    class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md">
                                </div>
                            @endif
                            <div class="flex items-center gap-3.5">
                                <i data-feather="trending-up"
                                    class="w-5 h-5 {{ $isPenjualan ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Penjualan</span>
                            </div>
                            <svg :class="{ 'rotate-180': open }"
                                class="w-4 h-4 transition-transform duration-200 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-collapse style="{{ $isPenjualan ? '' : 'display: none;' }}"
                            class="mt-1.5 space-y-1.5 pl-[52px] pr-2">
                            <li>
                                <a href="{{ route('transactions.index', ['type' => 'penjualan']) }}"
                                    title="Faktur Penjualan"
                                    class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ $isPenjualan ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">
                                    Faktur Penjualan
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Pembelian --}}
                    @php $isPembelian = request()->routeIs('transactions.*') && request()->query('type') == 'pembelian'; @endphp
                    <div x-data="{ open: {{ $isPembelian ? 'true' : 'false' }} }">
                        <button @click="open = !open" title="Menu Pembelian"
                            class="w-full relative flex items-center justify-between px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ $isPembelian ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if ($isPembelian)
                                <div
                                    class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md">
                                </div>
                            @endif
                            <div class="flex items-center gap-3.5">
                                <i data-feather="shopping-bag"
                                    class="w-5 h-5 {{ $isPembelian ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Pembelian</span>
                            </div>
                            <svg :class="{ 'rotate-180': open }"
                                class="w-4 h-4 transition-transform duration-200 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-collapse style="{{ $isPembelian ? '' : 'display: none;' }}"
                            class="mt-1.5 space-y-1.5 pl-[52px] pr-2">
                            <li>
                                <a href="{{ route('transactions.index', ['type' => 'pembelian']) }}"
                                    title="Faktur Pembelian"
                                    class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ $isPembelian ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">
                                    Faktur Pembelian
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Biaya & Domba (Manajemen) --}}
                    @php $isBiaya = request()->routeIs('sheep.*') || request()->routeIs('growths.*'); @endphp
                    <div x-data="{ open: {{ $isBiaya ? 'true' : 'false' }} }">
                        <button @click="open = !open" title="Menu Biaya & Domba"
                            class="w-full relative flex items-center justify-between px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ $isBiaya ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if ($isBiaya)
                                <div
                                    class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md">
                                </div>
                            @endif
                            <div class="flex items-center gap-3.5">
                                <i data-feather="folder"
                                    class="w-5 h-5 {{ $isBiaya ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Biaya & Domba</span>
                            </div>
                            <svg :class="{ 'rotate-180': open }"
                                class="w-4 h-4 transition-transform duration-200 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-collapse style="{{ $isBiaya ? '' : 'display: none;' }}"
                            class="mt-1.5 space-y-1.5 pl-[52px] pr-2">
                            <li>
                                <a href="{{ route('sheep.index') }}" title="Daftar Domba"
                                    class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('sheep.*') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">Daftar
                                    Domba</a>
                            </li>
                            <li>
                                <a href="{{ route('growths.index') }}" title="Pertumbuhan Domba"
                                    class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('growths.*') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">Pertumbuhan</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Kas & Bank --}}
                    @php $isKas = request()->routeIs('finances.*') || request()->routeIs('bank-accounts.*'); @endphp
                    <div x-data="{ open: {{ $isKas ? 'true' : 'false' }} }">
                        <button @click="open = !open" title="Menu Kas & Bank"
                            class="w-full relative flex items-center justify-between px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ $isKas ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if ($isKas)
                                <div
                                    class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md">
                                </div>
                            @endif
                            <div class="flex items-center gap-3.5">
                                <i data-feather="briefcase"
                                    class="w-5 h-5 {{ $isKas ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Kas & Bank</span>
                            </div>
                            <svg :class="{ 'rotate-180': open }"
                                class="w-4 h-4 transition-transform duration-200 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-collapse style="{{ $isKas ? '' : 'display: none;' }}"
                            class="mt-1.5 space-y-1.5 pl-[52px] pr-2">
                            <li>
                                <a href="{{ route('finances.index') }}" title="Arus Kas"
                                    class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('finances.*') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">Arus
                                    Kas</a>
                            </li>
                            <li>
                                <a href="{{ route('bank-accounts.index') }}" title="Rekening Bank"
                                    class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('bank-accounts.*') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">Rekening
                                    Bank</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Kontak --}}
                    @php $isKontak = request()->routeIs('customers.*') || request()->routeIs('suppliers.*'); @endphp
                    <div>
                        <a href="{{ route('customers.index') }}" title="Kontak Pelanggan"
                            class="relative flex items-center justify-between px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ request()->routeIs('customers.*') ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if (request()->routeIs('customers.*'))
                                <div
                                    class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md">
                                </div>
                            @endif
                            <div class="flex items-center gap-3.5">
                                <i data-feather="users"
                                    class="w-5 h-5 {{ request()->routeIs('customers.*') ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Kontak</span>
                            </div>
                        </a>
                    </div>

                    {{-- Supplier / Lainnya --}}
                    <div>
                        <a href="{{ route('suppliers.index') }}" title="Supplier"
                            class="relative flex items-center justify-between px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ request()->routeIs('suppliers.*') ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if (request()->routeIs('suppliers.*'))
                                <div
                                    class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md">
                                </div>
                            @endif
                            <div class="flex items-center gap-3.5">
                                <i data-feather="truck"
                                    class="w-5 h-5 {{ request()->routeIs('suppliers.*') ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Supplier</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN BAWAH (Profil) --}}
    <div class="p-5 mt-auto" x-data="{ open: false }">
        <div
            class="bg-black/20 rounded-2xl shadow-inner ring-1 ring-white/10 backdrop-blur-lg transition-all duration-200">
            <button @click="open = !open" title="Profil & Pengaturan"
                class="w-full flex items-center gap-4 p-4 text-left rounded-2xl hover:bg-white/5 transition-all">
                <div
                    class="h-10 w-10 flex-shrink-0 rounded-full bg-white flex items-center justify-center text-[#03235b] font-extrabold text-lg uppercase shadow-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden leading-tight flex-1">
                    <div class="font-extrabold text-white truncate text-sm">{{ Auth::user()->name }}</div>
                    <div class="text-[11px] text-white/70 truncate">{{ Auth::user()->email }}</div>
                </div>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 text-white/50 transition-transform duration-200"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                style="display: none;" class="px-4 pb-4">
                <div class="border-t border-white/10 pt-3 mt-1 grid grid-cols-2 gap-2">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center justify-center py-2 text-xs font-extrabold text-white bg-white/10 rounded-lg hover:bg-white/20 transition-all">
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button
                            class="w-full py-2 text-xs font-extrabold text-white bg-rose-500/80 rounded-lg hover:bg-rose-500 transition-all shadow-sm">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
