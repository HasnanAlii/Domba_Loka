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

    <div class="min-h-screen bg-[#f0f6ff] px-10 py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                <div class="p-6 lg:p-10">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-slate-800">Form Domba</h3>
                        <p class="mt-1 text-sm text-slate-500">Masukkan data identitas domba dengan lengkap.</p>
                    </div>

                    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" data-ajax-form>
                        @csrf
                        @if ($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="mb-10 space-y-10 border-b border-slate-100 pb-10">
                            <!-- Main Photo -->
                            <div x-data="{
                                preview: '{{ $sheep->photo ? asset('storage/' . $sheep->photo) : '' }}',
                                handleUpload(e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        this.preview = URL.createObjectURL(file);
                                    }
                                }
                            }" class="relative mx-auto w-full max-w-sm">
                                <label
                                    class="mb-3 block text-center text-[10px] font-black uppercase tracking-[0.2em] text-[#03235b]">Foto
                                    Utama (Cover)</label>
                                <div class="relative group h-48 w-full border-2 border-dashed border-slate-200 rounded-3xl overflow-hidden bg-slate-50 transition-all hover:border-blue-400 hover:bg-blue-50/30 cursor-pointer"
                                    @click="$refs.mainPhotoInput.click()">

                                    <input type="file" name="photo" x-ref="mainPhotoInput" class="hidden"
                                        accept="image/*" @change="handleUpload">

                                    <div x-show="!preview"
                                        class="flex flex-col items-center justify-center h-full space-y-2">
                                        <div
                                            class="p-3 bg-white rounded-2xl shadow-sm text-slate-400 group-hover:text-blue-500 group-hover:scale-110 transition-all">
                                            <i data-feather="image" class="w-6 h-6"></i>
                                        </div>
                                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pilih
                                            Foto Utama</p>
                                    </div>

                                    <template x-if="preview">
                                        <div class="relative h-full w-full">
                                            <img :src="preview" class="h-full w-full object-cover">
                                            <div
                                                class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                                <span
                                                    class="px-4 py-2 bg-white/30 backdrop-blur-md rounded-full text-white text-[10px] font-black uppercase tracking-widest border border-white/40">Ganti
                                                    Utama</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                @error('photo')
                                    <p class="mt-2 text-center text-xs text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Additional Gallery -->
                            <div x-data="{
                                previews: [],
                                existingPhotos: [
                                    @foreach ($sheep->photos as $p)
                                        { id: {{ $p->id }}, path: '{{ asset('storage/' . $p->path) }}' }, @endforeach
                                ],
                                handleUpload(e) {
                                    const files = Array.from(e.target.files);
                                    this.previews = [];
                                    files.forEach(file => {
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            this.previews.push(e.target.result);
                                        };
                                        reader.readAsDataURL(file);
                                    });
                                }
                            }" class="space-y-6">
                                <label
                                    class="block text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Galeri
                                    Foto Tambahan</label>

                                <div class="relative group h-32 w-full border-2 border-dashed border-slate-100 rounded-[2rem] overflow-hidden bg-slate-50/50 transition-all hover:border-emerald-400 hover:bg-emerald-50/30 cursor-pointer flex flex-col items-center justify-center space-y-2"
                                    @click="$refs.galleryInput.click()">

                                    <input type="file" name="additional_photos[]" x-ref="galleryInput" class="hidden"
                                        accept="image/*" multiple @change="handleUpload">

                                    <div
                                        class="p-2 bg-white rounded-xl shadow-sm text-slate-300 group-hover:text-emerald-500 transition-all">
                                        <i data-feather="plus" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tambah ke
                                        Galeri</p>
                                </div>

                                <!-- Grid View -->
                                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-4">
                                    <!-- Existing -->
                                    <template x-for="photo in existingPhotos" :key="photo.id">
                                        <div
                                            class="relative aspect-square rounded-2xl overflow-hidden border border-slate-100 shadow-sm group/edit">
                                            <img :src="photo.path" class="h-full w-full object-cover">
                                            <div
                                                class="absolute inset-0 bg-black/40 opacity-0 group-hover/edit:opacity-100 transition-opacity flex items-center justify-center">
                                                <i data-feather="check" class="text-white w-5 h-5"></i>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- New Previews -->
                                    <template x-for="(src, index) in previews" :key="'new-' + index">
                                        <div
                                            class="relative aspect-square rounded-2xl overflow-hidden border-2 border-emerald-100 shadow-inner">
                                            <img :src="src" class="h-full w-full object-cover">
                                            <div
                                                class="absolute top-1 right-1 w-2 h-2 rounded-full bg-emerald-500 animate-pulse">
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                @error('additional_photos.*')
                                    <p class="mt-2 text-center text-xs text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="code" class="mb-2 block text-sm font-semibold text-slate-700">Kode
                                    Domba/Tag ID <span class="text-rose-500">*</span></label>
                                <input type="text" name="code" id="code"
                                    value="{{ old('code', $sheep->code) }}" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-mono text-slate-600 transition-all focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="Masukkan Kode Domba">
                                @error('code')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type_id" class="mb-2 block text-sm font-semibold text-slate-700">Jenis
                                    Domba <span class="text-rose-500">*</span></label>
                                @php
                                    $sheepTypeOptions = $sheepTypes
                                        ->map(
                                            fn($t) => [
                                                'id' => $t->id,
                                                'name' => $t->name,
                                            ],
                                        )
                                        ->toArray();
                                @endphp
                                <x-searchable-dropdown name="type_id" id="type_id" placeholder="Pilih Jenis Domba..."
                                    buttonText="Tambah Jenis" :buttonRoute="route('sheep-types.create')" :showFooter="true" limit="5"
                                    :options="$sheepTypeOptions" :value="old('type_id', $sheep->type_id)" />
                                @error('type_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="weight" class="mb-2 block text-sm font-semibold text-slate-700">Berat (Kg)
                                    <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" name="weight" id="weight"
                                    value="{{ old('weight', $sheep->weight) }}" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-600 transition-all focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="Masukkan Berat">
                                @error('weight')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="age" class="mb-2 block text-sm font-semibold text-slate-700">Umur
                                    (Bulan) <span class="text-rose-500">*</span></label>
                                <input type="number" name="age" id="age"
                                    value="{{ old('age', $sheep->age) }}" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-600 transition-all focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="Masukkan Umur">
                                @error('age')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="condition" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Kondisi Fisik <span class="text-rose-500">*</span>
                                </label>

                                <div x-data="{
                                    open: false,
                                    selected: '{{ old('condition', $sheep->condition ?? 'Sehat') }}',
                                    options: ['Sehat', 'Sakit Ringan', 'Sakit Parah', 'Cacat'],
                                    select(option) {
                                        this.selected = option;
                                        this.open = false;
                                    }
                                }" class="relative">
                                    <input type="hidden" name="condition" :value="selected">
                                    <div @click="open = !open"
                                        class="relative flex items-center bg-white border border-slate-200 rounded-xl transition-all duration-300 cursor-pointer hover:border-blue-300 focus-within:ring-4 focus-within:ring-blue-500/10"
                                        :class="open ? 'border-blue-400 bg-white ring-4 ring-blue-500/10' : ''">
                                        <div class="w-full px-4 py-3 text-[14px] text-slate-700 font-bold"
                                            x-text="selected"></div>
                                        <div
                                            class="px-3.5 py-3 border-l border-slate-100 bg-slate-50/50 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-300"
                                                :class="open ? 'rotate-180 text-blue-500' : ''"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div x-show="open" x-cloak @click.outside="open = false" x-transition
                                        class="absolute z-50 w-full mt-1.5 bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden">
                                        <div class="py-1">
                                            <template x-for="option in options" :key="option">
                                                <div @click="select(option)"
                                                    class="px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-all duration-200 flex items-center justify-between group">
                                                    <span class="text-[13px] font-bold transition-colors"
                                                        :class="selected === option ? 'text-blue-600' :
                                                            'text-slate-700 group-hover:text-blue-600'"
                                                        x-text="option"></span>
                                                    <div x-show="selected === option"
                                                        class="w-4 h-4 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-2.5 h-2.5 text-blue-600" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                @error('condition')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <input type="hidden" name="status"
                                value="{{ old('status', $sheep->status ?? 'tersedia') }}">


                            <div x-data="{
                                display: '{{ old('price', $sheep->price) }}',
                                raw: '{{ old('price', $sheep->price) }}',
                                format() {
                                    let number = String(this.display).replace(/\D/g, '');
                                    this.raw = number;
                                    this.display = number ? new Intl.NumberFormat('id-ID').format(number) : '';
                                }
                            }" x-init="format()">
                                <label for="price_display"
                                    class="mb-2 block text-sm font-semibold text-slate-700">Harga (Rp) <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" id="price_display" x-model="display" @input="format" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-600 font-bold transition-all focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="Masukkan Harga Domba">
                                <input type="hidden" name="price" :value="raw">
                                @error('price')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('sheep.index') }}"
                                class="border border-slate-200 rounded-2xl bg-transparent hover:bg-slate-50/50 text-center px-6 py-3 font-medium text-slate-500 transition-colors hover:text-slate-700">Batal</a>
                            <button type="submit"
                                class="transform rounded-2xl bg-blue-600 px-8 py-3.5 font-black text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-blue-600/40">
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
