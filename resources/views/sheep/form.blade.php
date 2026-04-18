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
            <div class="rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
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
                                @php
                                    $sheepTypeOptions = $sheepTypes->map(fn($t) => [
                                        'id' => $t->id,
                                        'name' => $t->name
                                    ])->toArray();
                                @endphp
                                <x-searchable-dropdown 
                                    name="type_id" 
                                    id="type_id" 
                                    placeholder="Pilih Jenis Domba..."
                                    buttonText="Tambah Jenis"
                                    :buttonRoute="route('sheep-types.create')"
                                    :showFooter="true"
                                    limit="5"
                                    :options="$sheepTypeOptions"
                                    :value="old('type_id', $sheep->type_id)"
                                />
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

                                    <!-- Trigger -->
                                    <div 
                                        @click="open = !open"
                                        class="relative flex items-center bg-slate-50/50 border border-slate-200 rounded-xl transition-all duration-300 cursor-pointer hover:border-blue-300 focus-within:ring-4 focus-within:ring-blue-500/10"
                                        :class="open ? 'border-blue-400 bg-white ring-4 ring-blue-500/10' : ''"
                                    >
                                        <div class="w-full px-4 py-3 text-[16px] text-slate-700 font-medium" x-text="selected"></div>
                                        
                                        <div class="px-3.5 py-3 border-l border-slate-100 bg-slate-50/50 flex items-center justify-center">
                                            <svg 
                                                class="w-4 h-4 text-slate-400 transition-transform duration-300" 
                                                :class="open ? 'rotate-180 text-blue-500' : ''"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Dropdown Menu -->
                                    <div 
                                        x-show="open" 
                                        x-cloak
                                        @click.outside="open = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 translate-y-1"
                                        class="absolute z-50 w-full mt-1.5 bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/[0.02]"
                                    >
                                        <div class="py-1">
                                            <template x-for="option in options" :key="option">
                                                <div 
                                                    @click="select(option)"
                                                    class="px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition-all duration-200 flex items-center justify-between group"
                                                >
                                                    <span 
                                                        class="text-[14px] font-bold tracking-tight transition-colors"
                                                        :class="selected === option ? 'text-blue-600' : 'text-slate-700 group-hover:text-blue-600'"
                                                        x-text="option"
                                                    ></span>
                                                    
                                                    <div x-show="selected === option" class="w-4 h-4 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-2.5 h-2.5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
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
                            <a href="{{ route('sheep.index') }}" class="border border-slate-200 rounded-2xl bg-transparent hover:bg-slate-50/50 text-center px-6 py-3 font-medium text-slate-500 transition-colors hover:text-slate-700">Batal</a>
                            <button type="submit" class="transform rounded-2xl bg-blue-600 px-8 py-3.5 font-black text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-blue-600/40">
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