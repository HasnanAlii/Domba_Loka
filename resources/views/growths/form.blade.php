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

    <div class="min-h-screen  bg-[#f0f6ff] px-10 py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                <div class="p-6 lg:p-10">
                    <form action="{{ $action }}" method="POST" data-ajax-form>
                        @csrf
                        @if ($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2"
                            x-data="{
                                sheepWeight: {{ $growth->exists ? (float)$growth->previous_weight : 0 }},
                                actualWeight: {{ old('actual_weight', $growth->exists ? (float)$growth->actual_weight : '') ?: 0 }},
                                get gain() { return (this.actualWeight - this.sheepWeight).toFixed(2); },
                                get gainNum() { return this.actualWeight - this.sheepWeight; }
                            }">
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
                                        'name' => $s->code . ' (' . ($s->sheepType->name ?? '-') . ') — ' . $s->weight . ' kg'
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

                            {{-- Info berat sebelumnya --}}
                            <div class="md:col-span-2">
                                <div x-show="sheepWeight > 0"
                                    class="flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-2xl px-5 py-3 text-sm">
                                    <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z"/>
                                    </svg>
                                    <span class="text-blue-700 font-semibold">
                                        Berat domba sebelum penimbangan:
                                        <strong x-text="sheepWeight + ' kg'"></strong>
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label for="actual_weight" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Berat Aktual Sekarang (Kg) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" step="0.01" name="actual_weight" id="actual_weight"
                                    x-model.number="actualWeight"
                                    value="{{ old('actual_weight', $growth->exists ? $growth->actual_weight : '') }}"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                                    placeholder="Masukkan Berat Aktual Saat Ini">
                                @error('actual_weight')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror

                                {{-- Preview kenaikan berat --}}
                                <div x-show="actualWeight > 0 && sheepWeight > 0" class="mt-2 text-xs font-bold"
                                    :class="gainNum >= 0 ? 'text-emerald-600' : 'text-rose-500'">
                                    Kenaikan berat: <span x-text="(gainNum >= 0 ? '+' : '') + gain + ' kg'"></span>
                                </div>
                            </div>

                            <div>
                                <label for="target" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Target Kenaikan Berat (Kg) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" step="0.01" name="target" id="target"
                                    value="{{ old('target', $growth->target) }}"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                                    placeholder="Masukkan Target Kenaikan Berat">
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

    <script>
        // Map sheep_id => weight for live previous weight display
        const sheepWeightMap = @json($sheep->pluck('weight', 'id'));

        document.addEventListener('DOMContentLoaded', () => {
            const sheepSelect = document.querySelector('#sheep_id');
            const gridEl = sheepSelect ? sheepSelect.closest('[x-data]') : null;

            function syncSheepWeight(id) {
                if (!id || !gridEl) return;
                const weight = sheepWeightMap[id];
                if (weight !== undefined) {
                    Alpine.$data(gridEl).sheepWeight = parseFloat(weight);
                }
            }

            if (sheepSelect) {
                sheepSelect.addEventListener('change', () => syncSheepWeight(sheepSelect.value));
                new MutationObserver(() => syncSheepWeight(sheepSelect.value))
                    .observe(sheepSelect, { attributes: true, attributeFilter: ['value'] });
                // Sync on initial load (edit mode)
                if (sheepSelect.value) syncSheepWeight(sheepSelect.value);
            }
        });
    </script>
</x-app-layout>
