<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-extrabold text-2xl leading-tight tracking-tight text-gray-800">{{ __($pageTitle) }}</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('growths.index') }}" class="transition hover:text-blue-600">Pertumbuhan</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Form</span>
            </nav>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 px-10 py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                <div class="p-6 lg:p-10">
                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @if ($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="date" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Penimbangan <span class="text-rose-500">*</span></label>
                                <input type="date" name="date" id="date" value="{{ old('date', $growth->date ? \Illuminate\Support\Carbon::parse($growth->date)->format('Y-m-d') : date('Y-m-d')) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                @error('date')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="sheep_id" class="mb-2 block text-sm font-semibold text-slate-700">Pilih Domba <span class="text-rose-500">*</span></label>
                                @php
                                    $sheepOptions = $sheep->map(fn($s) => [
                                        'id' => $s->id,
                                        'name' => $s->code . ' (' . ($s->sheepType->name ?? '-') . ')'
                                    ])->toArray();
                                @endphp
                                <x-searchable-dropdown 
                                    name="sheep_id" 
                                    id="sheep_id" 
                                    placeholder="Cari Domba..."
                                    buttonText="Tambah Domba"
                                    limit="5"
                                    :buttonRoute="route('sheep.create')"
                                    :options="$sheepOptions"
                                    :value="old('sheep_id', $growth->sheep_id)"
                                />
                                @error('sheep_id')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="weight" class="mb-2 block text-sm font-semibold text-slate-700">Berat Aktual (Kg) <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" name="weight" id="weight" value="{{ old('weight', $growth->weight) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20" placeholder="Masukkan Berat Aktual">
                                @error('weight')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="target" class="mb-2 block text-sm font-semibold text-slate-700">Target Berat (Kg) <span class="text-rose-500">*</span></label>
                                <input type="number" step="0.01" name="target" id="target" value="{{ old('target', $growth->target) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20" placeholder="Masukkan Target Berat">
                                @error('target')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('growths.index') }}" class="border border-slate-200 rounded-2xl bg-transparent hover:bg-slate-50/50 text-center px-6 py-3 font-medium text-slate-500 transition-colors hover:text-slate-700">Batal</a>
                            <button type="submit" class="transform rounded-2xl bg-blue-600 px-8 py-3.5 font-black text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-blue-600/40">{{ $submitLabel }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('growths.script')
</x-app-layout>
