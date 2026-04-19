<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-800">{{ $pageTitle }}</h2>
                <p class="mt-1 text-sm text-slate-500">Gunakan form yang sama untuk tambah maupun edit data keuangan.</p>
            </div>
            
            <a href="{{ route('finances.index') }}" class="text-sm font-semibold text-blue-600 transition hover:text-blue-700">
                Kembali ke daftar
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen  bg-[#f0f6ff] px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <div class="rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                <form action="{{ $action }}" method="POST" class="p-8 lg:p-12 space-y-10">
                    @csrf
                    @if ($method !== 'POST')
                        @method($method)
                    @endif

                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <!-- Jenis Transaksi -->
                        <div class="relative group pt-2" x-data="{ open: false, selected: '{{ old('type', $finance->type ?? 'income') }}' }">
                            <label class="absolute -top-1 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm z-20 transition-colors group-focus-within:text-blue-500 uppercase tracking-wider">Jenis Transaksi</label>
                            <input type="hidden" name="type" :value="selected">
                            
                            <div @click="open = !open" class="relative flex items-center bg-white border border-slate-200 rounded-2xl transition-all duration-300 cursor-pointer hover:border-blue-300 py-3.5 px-4 shadow-sm" :class="open ? 'border-blue-400 ring-4 ring-blue-500/10' : ''">
                                <div class="flex items-center gap-3">
                                    <template x-if="selected === 'income'">
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></div>
                                    </template>
                                    <template x-if="selected === 'expense'">
                                        <div class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]"></div>
                                    </template>
                                    <span class="text-[16px] font-black text-slate-700" x-text="selected === 'income' ? 'Pemasukan (Income)' : 'Pengeluaran (Expense)'"></span>
                                </div>
                                <div class="ml-auto">
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>

                            <div x-show="open" x-cloak @click.outside="open = false" x-transition.opacity class="absolute z-50 w-full mt-2 bg-white border border-slate-100 rounded-[24px] shadow-2xl overflow-hidden py-1.5 ring-1 ring-black/[0.02]">
                                <div @click="selected = 'income'; open = false" class="px-5 py-3.5 hover:bg-emerald-50 cursor-pointer transition-all flex items-center gap-3 group">
                                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm opacity-40 group-hover:opacity-100 transition-opacity"></div>
                                    <span class="text-sm font-black text-slate-700 group-hover:text-emerald-700">Pemasukan (Income)</span>
                                </div>
                                <div @click="selected = 'expense'; open = false" class="px-5 py-3.5 hover:bg-rose-50 cursor-pointer transition-all flex items-center gap-3 group border-t border-gray-50">
                                    <div class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-sm opacity-40 group-hover:opacity-100 transition-opacity"></div>
                                    <span class="text-sm font-black text-slate-700 group-hover:text-rose-700">Pengeluaran (Expense)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Nominal Jumlah -->
                        <div class="relative group pt-2" x-data="{
                            display: '{{ old('amount', $finance->amount) }}',
                            raw: '{{ old('amount', $finance->amount) }}',
                            format() {
                                let number = this.display.replace(/\D/g, '');
                                this.raw = number;
                                this.display = number ? new Intl.NumberFormat('id-ID').format(number) : '';
                            }
                        }" x-init="format()">
                            <label for="amount_display" class="absolute -top-1 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm z-20 transition-colors group-focus-within:text-blue-500 uppercase tracking-wider">Jumlah Nominal (Rp)</label>
                            <div class="relative items-center flex bg-white border border-slate-200 rounded-2xl transition-all duration-300 hover:border-blue-300 shadow-sm focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10">
                                <span class="pl-4 text-slate-400 font-bold text-sm">Rp</span>
                                <input id="amount_display" type="text" x-model="display" @input="format" class="w-full bg-transparent border-none text-[18px] font-black text-slate-800 px-2 py-3.5 focus:ring-0 outline-none" placeholder="0">
                            </div>
                            <input type="hidden" name="amount" :value="raw">
                            @error('amount')<p class="mt-2 text-[11px] font-bold text-rose-500 ml-4">{{ $message }}</p>@enderror
                        </div>

                        <!-- Rekening Bank -->
                        <div class="relative group pt-2 md:col-span-2">
                            <label class="absolute -top-1 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm z-20 uppercase tracking-wider">Rekening Bank</label>
                            @php
                                $bankOptions = $bankAccounts->map(fn($b) => [
                                    'id' => $b->id,
                                    'name' => $b->bank_name . ' - ' . $b->account_number
                                ])->toArray();
                            @endphp
                            <x-searchable-dropdown 
                                name="bank_account_id" 
                                id="bank_account_id" 
                                placeholder="Pilih Rekening..."
                                :options="$bankOptions"
                                limit="5"
                                :value="old('bank_account_id', $finance->bank_account_id)"
                            />
                            @error('bank_account_id') <p class="mt-2 text-[11px] font-bold text-rose-500 ml-4">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="relative group pt-2">
                        <label for="description" class="absolute -top-1 left-3 bg-white px-1.5 text-[11px] font-bold text-gray-400 border border-gray-100 rounded shadow-sm z-20 uppercase tracking-wider">Keterangan / Deskripsi</label>
                        <textarea id="description" name="description" rows="4" class="w-full rounded-[24px] border-slate-200 text-[15px] font-medium text-slate-700 bg-slate-50/30 p-5 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" placeholder="Tambahkan catatan jika perlu...">{{ old('description', $finance->description) }}</textarea>
                        @error('description')<p class="mt-2 text-[11px] font-bold text-rose-500 ml-4">{{ $message }}</p>@enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 border-t border-slate-100 pt-10">
                        <a href="{{ route('finances.index') }}" class="px-6 py-3 font-semibold text-slate-500 transition-colors hover:text-slate-700 border border-slate-200 rounded-2xl bg-transparent hover:bg-slate-50/50 text-center">
                            Batal
                        </a>
                        <button type="submit" class="transform rounded-2xl bg-blue-600 px-8 py-3.5 font-black text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-blue-600/40">
                            {{ $submitLabel }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('finances.script', ['page' => 'form'])
</x-app-layout>