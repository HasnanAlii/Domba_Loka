<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-extrabold text-2xl leading-tight tracking-tight text-gray-800">{{ __($pageTitle) }}</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('suppliers.index') }}" class="transition hover:text-blue-600">Supplier</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Form</span>
            </nav>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 px-10 py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                <div class="p-6 lg:p-10">
                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @if ($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="md:col-span-2">
                                <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20" placeholder="Masukkan Nama Lengkap">
                                @error('name')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">No. Telepon <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $supplier->phone) }}" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20" placeholder="Masukkan No. Telepon">
                                @error('phone')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20" placeholder="Masukkan Alamat Email">
                                @error('email')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="address" class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
                                <textarea name="address" id="address" rows="4" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-slate-600 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20" placeholder="Masukkan Alamat Lengkap">{{ old('address', $supplier->address) }}</textarea>
                                @error('address')<p class="mt-2 text-sm text-rose-500">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('suppliers.index') }}" class="px-6 py-3 font-medium text-slate-500 transition-colors hover:text-slate-700">Batal</a>
                            <button type="submit" class="transform rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-blue-600/40">{{ $submitLabel }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('suppliers.script')
</x-app-layout>
