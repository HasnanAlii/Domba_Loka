@props([
    'placeholder' => 'Cari produk...',
    'buttonText' => 'Tambahkan',
    'buttonRoute' => null,
    'showFooter' => true,
    'options' => [],
    'name' => '',
    'id' => '',
    'value' => null,
    'limit' => 0,
    'compact' => false,
])

<div x-data="{
    open: false,
    search: '',
    selected: @js($value),
    items: @js($options),
    limit: {{ (int) $limit }},
    init() {
        if (this.selected) {
            const item = this.items.find(i => i.id == this.selected);
            if (item) this.search = item.name;
        }

        // Watch for external value changes (e.g. from parent Alpine data)
        this.$watch('selected', (val) => {
            if (val) {
                const item = this.items.find(i => i.id == val);
                if (item) this.search = item.name;
            } else {
                this.search = '';
            }
        });
    },
    get filteredItems() {
        let filtered = this.items;
        if (this.search !== '' && !this.selected) {
            filtered = this.items.filter(item =>
                item.name.toLowerCase().includes(this.search.toLowerCase())
            );
        }

        return this.limit > 0 ? filtered.slice(0, this.limit) : filtered;
    },
    selectItem(item) {
        this.selected = item.id;
        this.search = item.name;
        this.open = false;

        // Use $nextTick to ensure x-model is updated before dispatching events
        this.$nextTick(() => {
            let input = this.$el.querySelector('input[type=hidden]');
            if (input) {
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });

        this.$dispatch('selected', item);
    }
}" x-modelable="selected" :class="open ? 'z-[100]' : 'z-10'" @click.outside="open = false"
    class="relative w-full group select-none">

    @php
        $disabledAttr =
            $attributes->get(':disabled') ??
            ($attributes->get('::disabled') ??
                ($attributes->get('x-bind:disabled') ?? ($attributes->has('disabled') ? 'true' : 'false')));
    @endphp

    <input type="hidden" {{ $attributes->merge(['name' => $name, 'id' => $id]) }} x-model="selected">

    <!-- Trigger / Input Area -->
    <div @click="open = !open"
        class="relative flex items-center bg-white border border-slate-200 rounded-xl transition-all duration-300 cursor-pointer hover:border-blue-300 shadow-sm"
        :class="open ? 'border-blue-400 ring-4 ring-blue-500/10' : ''">

        <!-- Input Field -->
        <input type="text" x-model="search" @focus="open = true" @input="selected = null; open = true" @click.stop
            class="w-full px-4 {{ $compact ? 'py-1.5 text-[13px]' : 'py-3 text-[16px]' }} text-slate-700 bg-transparent border-none focus:ring-0 placeholder-slate-400 font-medium"
            placeholder="{{ $placeholder }}" autocomplete="off">

        <!-- Search Icon -->
        <div class="px-3 border-l border-slate-100 flex items-center justify-center {{ $compact ? 'py-1.5' : 'py-3' }}">
            <svg class="w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors duration-300" 
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <!-- Dropdown Content -->
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="absolute z-[60] w-full mt-1.5 bg-white border border-slate-100 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/[0.02]">
        <!-- Items List -->
        <div class="max-h-[220px] overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
            <template x-for="item in filteredItems" :key="item.id">
                <div @click="selectItem(item)"
                    class="px-4 py-2.5 cursor-pointer hover:bg-slate-50/80 transition-all duration-200 group/item border-b border-slate-50 last:border-0 flex items-center justify-between">
                    <span
                        class="text-[14px] font-bold text-slate-800 tracking-tight group-hover/item:text-blue-600 transition-colors"
                        x-text="item.name"></span>

                    <div x-show="selected == item.id"
                        class="w-4 h-4 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-2.5 h-2.5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="filteredItems.length === 0" class="px-4 py-6 text-center">
                <div
                    class="inline-flex items-center justify-center w-10 h-10 bg-slate-50 rounded-xl mb-3 text-slate-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <p class="text-[12px] text-slate-500 font-medium italic">Hasil tidak ditemukan.</p>
            </div>
        </div>

        @if ($showFooter)
            <!-- Sticky Footer Action -->
            <div class="bg-slate-50 p-2 flex justify-center border-t border-slate-100">
                @if ($buttonRoute)
                    <a href="{{ $buttonRoute }}"
                        class="group/btn relative flex items-center space-x-2 px-4 py-1.5 rounded-xl hover:bg-white hover:shadow-sm transition-all duration-300">
                        <div
                            class="bg-[#1a2b4b] rounded-full p-1 shadow-sm group-hover/btn:scale-110 transition-all duration-300">
                            <svg class="w-2.5 h-2.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span
                            class="text-[#1a2b4b] font-bold text-[10px] uppercase tracking-wider">{{ $buttonText }}</span>
                    </a>
                @else
                    <button type="button" @click="$dispatch('add-new'); open = false"
                        class="group/btn relative flex items-center space-x-2 px-4 py-1.5 rounded-xl hover:bg-white hover:shadow-sm transition-all duration-300">
                        <div
                            class="bg-[#1a2b4b] rounded-full p-1 shadow-sm group-hover/btn:scale-110 transition-all duration-300">
                            <svg class="w-2.5 h-2.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span
                            class="text-[#1a2b4b] font-bold text-[10px] uppercase tracking-wider">{{ $buttonText }}</span>
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }

    /* Custom Scrollbar for better UI */
    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }
</style>
