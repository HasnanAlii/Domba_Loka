<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-extrabold text-2xl leading-tight tracking-tight text-gray-800">{{ __($pageTitle) }}</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('sheep.index') }}" class="transition hover:text-blue-600">Domba</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Form</span>
            </nav>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 px-10 py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                <div class="p-6 lg:p-10">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-slate-800">Form Domba</h3>
                        <p class="mt-1 text-sm text-slate-500">Masukkan data identitas domba dengan lengkap.</p>
                    </div>

                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @if ($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="code" class="mb-2 block text-sm font-semibold text-slate-700">Kode Domba/Tag ID <span class="text-rose-500">*</span></label>
                                <input type="text" name="code" id="code" value="{{ old('code', $sheep->code) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 font-mono text-slate-600 transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20" placeholder="Masukkan Kode Domba">
                                @error('code')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type_id" class="mb-2 block text-sm font-semibold text-slate-700">Jenis Domba <span class="text-rose-500">*</span></label>
                                <select name="type_id" id="type_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                    <option value="">Pilih Jenis Domba</option>
                                    @foreach($sheepTypes as $type)
                                        <option value="{{ $type->id }}" @selected(old('type_id', $sheep->type_id) == $type->id)>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('type_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="weight" class="mb-2 block text-sm font-semibold text-slate-700">Berat (Kg) <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight', $sheep->weight) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20" placeholder="Masukkan Berat">
                                @error('weight')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="condition" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Kondisi Fisik <span class="text-rose-500">*</span>
                                </label>

                                <select name="condition" id="condition" required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">

                                    @php
                                        $selected = old('condition', $sheep->condition ?? 'Sehat');
                                    @endphp

                                    <option value="Sehat" {{ $selected == 'Sehat' ? 'selected' : '' }}>Sehat</option>
                                    <option value="Sakit Ringan" {{ $selected == 'Sakit Ringan' ? 'selected' : '' }}>Sakit Ringan</option>
                                    <option value="Sakit Parah" {{ $selected == 'Sakit Parah' ? 'selected' : '' }}>Sakit Parah</option>
                                    <option value="Cacat" {{ $selected == 'Cacat' ? 'selected' : '' }}>Cacat</option>
                                </select>

                                @error('condition')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2"
                                x-data="{
                                    display: '{{ old('price', $sheep->price) }}',
                                    raw: '{{ old('price', $sheep->price) }}',
                                    format() {
                                        let number = this.display.replace(/\D/g, '');
                                        this.raw = number;
                                        this.display = number ? new Intl.NumberFormat('id-ID').format(number) : '';
                                    }
                                }"
                                x-init="format()">

                                <label for="price_display" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Harga (Rp) <span class="text-rose-500">*</span>
                                </label>

                                <!-- Input tampilan -->
                                <input type="text"
                                    id="price_display"
                                    x-model="display"
                                    @input="format"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                                    placeholder="Masukkan Harga Domba">

                                <!-- Input asli -->
                                <input type="hidden" name="price" :value="raw">

                                @error('price')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('sheep.index') }}" class="px-6 py-3 font-medium text-slate-500 transition-colors hover:text-slate-700">Batal</a>
                            <button type="submit" class="transform rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-blue-600/40">
                                {{ $submitLabel }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('sheep.script', ['page' => 'form'])
</x-app-layout>