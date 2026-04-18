<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Detail Rekening Bank') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('bank-accounts.index') }}" class="hover:text-blue-600 cursor-pointer transition">Rekening Bank</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail Data</span>
            </nav>
        </div>
    </x-slot>

    <div x-data="{ openMigrationModal: {{ $errors->has('amount') || $errors->has('description') ? 'true' : 'false' }} }" class="py-12 bg-slate-50 min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 shadow-xl shadow-blue-500/30 rounded-3xl p-6 lg:p-8 relative overflow-hidden group hover:shadow-md transition-all text-white">
                        <div class="absolute right-0 top-0 h-32 w-32 bg-white/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110 blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="p-4 bg-white/20 backdrop-blur-sm rounded-2xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white uppercase tracking-wider">{{ $bankAccount->bank_name }}</h3>
                                </div>
                            </div>
                            
                            <div class="space-y-4 pt-4 border-t border-white/20">
                                <div class="flex flex-col pb-2">
                                    <span class="text-xs font-semibold text-blue-100 uppercase tracking-wider mb-1">No. Rekening</span>
                                    <span class="text-white font-mono text-xl tracking-widest font-bold">{{ $bankAccount->account_number }}</span>
                                </div>
                                <div class="flex flex-col pb-2">
                                    <span class="text-xs font-semibold text-blue-100 uppercase tracking-wider mb-1">Nama Pemilik Rekening</span>
                                    <span class="text-white font-medium text-lg leading-relaxed">{{ $bankAccount->account_name }}</span>
                                </div>
                                <div class="flex flex-col pb-2">
                                    <span class="text-xs font-semibold text-blue-100 uppercase tracking-wider mb-1">Saldo Rekening</span>
                                    <span class="text-white font-bold text-xl leading-relaxed">Rp {{ number_format($bankAccount->saldo, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex items-center gap-3">
                                <a href="{{ route('bank-accounts.edit', $bankAccount) }}" 
                                   class="flex-1 text-center py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold rounded-xl transition-colors ring-1 ring-white/50">
                                    Edit Rekening
                                </a>
                            </div>

                            @if($bankAccount->account_number !== 'CASH-001')
                                <div class="mt-3">
                                    <button type="button"
                                        @click="openMigrationModal = true"
                                        class="w-full text-center py-2.5 bg-emerald-500/90 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-colors ring-1 ring-emerald-200/40">
                                        Migrasi Dana ke Rekening Ini
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                        <div class="p-6 lg:p-8">
                            @if(session('success'))
                                <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800">Riwayat Keuangan / Mutasi</h3>
                                    <p class="text-sm text-slate-500 mt-1">Daftar transaksi real yang menggunakan rekening ini.</p>
                                </div>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-slate-200">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Ref</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tipe</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pihak</th>
                                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Jumlah/Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @if($history->count() > 0)
                                            @foreach($history as $item)
                                            <tr class="hover:bg-blue-50/40 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-600">
                                                    {{ $item->date instanceof \Carbon\Carbon ? $item->date->format('d/m/Y') : \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-bold text-blue-600 font-mono tracking-wider">
                                                    {{ $item->ref }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                                    @if($item->type === 'masuk')
                                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                            Masuk
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                                            Keluar
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium text-slate-700">
                                                    {{ $item->party }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                                    <span class="text-sm font-bold font-mono tracking-tight text-slate-700">
                                                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">
                                                    Belum ada riwayat transaksi/mutasi pada rekening ini.
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

        <div x-show="openMigrationModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4" style="display: none;">
            <div @click.away="openMigrationModal = false" class="w-full max-w-lg rounded-2xl bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="text-base font-bold text-slate-800">Migrasi Dana Antar Rekening</h3>
                    <button type="button" @click="openMigrationModal = false" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('bank-accounts.migrate-cash', $bankAccount) }}" method="POST" class="space-y-4 px-6 py-5">
                    @csrf

                    <div>
                        <label for="from_bank_account_id" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Pilih Rekening Asal
                        </label>
                        @php
                            $accountOptions = $allAccounts->map(fn($acc) => [
                                'id' => $acc->id,
                                'name' => $acc->bank_name . ' - ' . $acc->account_number . ' (Saldo: Rp ' . number_format($acc->saldo, 0, ',', '.') . ')'
                            ])->toArray();
                        @endphp
                        <x-searchable-dropdown 
                            name="from_bank_account_id" 
                            id="from_bank_account_id" 
                            placeholder="Pilih Rekening Asal..."
                            buttonText="Tambah Rekening"
                            :buttonRoute="route('bank-accounts.create')"
                            :options="$accountOptions"
                            :value="old('from_bank_account_id')"
                        />
                        @error('from_bank_account_id')
                            <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{
                        display: '{{ old('amount') }}',
                        raw: '{{ old('amount') }}',
                        format() {
                            let number = this.display.replace(/\D/g, '');
                            this.raw = number;
                            this.display = number ? new Intl.NumberFormat('id-ID').format(number) : '';
                        }
                    }" x-init="format()">

                        <label for="amount_display" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">
                            Nominal Migrasi
                        </label>

                        <!-- Input tampilan -->
                        <input id="amount_display"
                            type="text"
                            x-model="display"
                            @input="format"
                            required
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            placeholder="Masukkan nominal">

                        <!-- Input asli -->
                        <input type="hidden" name="amount" :value="raw">

                        @error('amount')
                            <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500">Keterangan</label>
                        <textarea id="description" name="description" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="Opsional">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="openMigrationModal = false" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Batal
                        </button>
                        <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Migrasikan Dana
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
