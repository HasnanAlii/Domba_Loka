<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-extrabold text-2xl leading-tight tracking-tight text-gray-800">{{ __($pageTitle) }}</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('sheep-types.index') }}" class="transition hover:text-blue-600">Kategori Domba</a>
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
                        <h3 class="text-xl font-bold text-slate-800">Form Kategori Domba</h3>
                        <p class="mt-1 text-sm text-slate-500">Masukkan nama kategori atau jenis domba.</p>
                    </div>

                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @if ($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="space-y-6">
                            <div>
                                <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Jenis <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $type->name) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20" placeholder="Masukkan Jenis Domba">
                                @error('name')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi (Opsional)</label>
                                <textarea name="description" id="description" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20" placeholder="Berikan deskripsi singkat mengenai kategori domba ini...">{{ old('description', $type->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('sheep-types.index') }}" class="border border-slate-200 rounded-xl px-6 py-3 font-medium text-slate-500 transition-colors hover:text-slate-700">Batal</a>
                            <button type="submit" class="transform rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-blue-600/40">
                                {{ $submitLabel }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
