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

    <div class="min-h-screen bg-slate-50 px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                <form action="{{ $action }}" method="POST" class="space-y-8 p-6 lg:p-8">
                    @csrf
                    @if ($method !== 'POST')
                        @method($method)
                    @endif

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="type" class="mb-2 block text-sm font-semibold text-slate-700">Jenis</label>
                            <select id="type" name="type" class="w-full rounded-2xl border-slate-200 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500">
                                <option value="income" @selected(old('type', $finance->type) === 'income')>Pemasukan</option>
                                <option value="expense" @selected(old('type', $finance->type) === 'expense')>Pengeluaran</option>
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{
                            display: '{{ old('amount', $finance->amount) }}',
                            raw: '{{ old('amount', $finance->amount) }}',
                            format() {
                                let number = this.display.replace(/\D/g, '');
                                this.raw = number;
                                this.display = number ? new Intl.NumberFormat('id-ID').format(number) : '';
                            }
                        }" x-init="format()">

                            <label for="amount_display" class="mb-2 block text-sm font-semibold text-slate-700">
                                Jumlah
                            </label>

                            <!-- Input yang terlihat -->
                            <input id="amount_display"
                                type="text"
                                x-model="display"
                                @input="format"
                                class="w-full rounded-2xl border-slate-200 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Masukkan nominal" />

                            <!-- Input asli untuk backend -->
                            <input type="hidden" name="amount" :value="raw">

                            @error('amount')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="bank_account_id" class="mb-2 block text-sm font-semibold text-slate-700">Rekening Bank</label>
                            <select id="bank_account_id" name="bank_account_id" class="w-full rounded-2xl border-slate-200 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih rekening</option>
                                @foreach ($bankAccounts as $bankAccount)
                                    <option value="{{ $bankAccount->id }}" @selected((string) old('bank_account_id', $finance->bank_account_id) === (string) $bankAccount->id)>
                                        {{ $bankAccount->bank_name }} - {{ $bankAccount->account_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bank_account_id')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="transaction_id" class="mb-2 block text-sm font-semibold text-slate-700">Transaksi Terkait</label>
                            <select id="transaction_id" name="transaction_id" class="w-full rounded-2xl border-slate-200 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Tidak ada transaksi terkait</option>
                                @foreach ($transactions as $transaction)
                                    <option value="{{ $transaction->id }}" @selected((string) old('transaction_id', $finance->transaction_id) === (string) $transaction->id)>
                                        {{ $transaction->reference_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('transaction_id')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi</label>
                        <textarea id="description" name="description" rows="4" class="w-full rounded-2xl border-slate-200 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500" placeholder="Tambahkan deskripsi singkat">{{ old('description', $finance->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                        <a href="{{ route('finances.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700">
                            {{ $submitLabel }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('finances.script', ['page' => 'form'])
</x-app-layout>