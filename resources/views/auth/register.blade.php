<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-2xl font-black tracking-tight text-slate-800">Daftar Akun</h2>
        <p class="mt-2 text-sm font-medium text-slate-500">Bergabunglah dengan ekosistem Domba Loka</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div class="space-y-1.5">
            <label for="name" class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-400 ml-1">Nama Lengkap</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0c5197] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama Anda"
                    class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 border-2 border-slate-200  rounded-xl text-sm font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:border-[#2ee0a7] focus:ring-4 focus:ring-[#2ee0a7]/10 transition-all duration-300">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-400 ml-1">Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0c5197] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan Email"
                    class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 border-2 border-slate-200  rounded-xl text-sm font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:border-[#2ee0a7] focus:ring-4 focus:ring-[#2ee0a7]/10 transition-all duration-300">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <label for="password" class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-400 ml-1">Kata Sandi</label>
            <div class="relative group" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0c5197] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" x-bind:type="show ? 'text' : 'password'" name="password" required placeholder="Masukkan Kata Sandi"
                    class="block w-full pl-12 pr-12 py-3.5 bg-slate-50 border-2 border-slate-200  rounded-xl text-sm font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:border-[#2ee0a7] focus:ring-4 focus:ring-[#2ee0a7]/10 transition-all duration-300">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-[#0c5197] focus:outline-none transition-colors">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 014.182-5.474M9.88 9.88A3 3 0 1014.12 14.12M15 12a3 3 0 00-3-3m-8.5 8.5l15-15" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <label for="password_confirmation" class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-400 ml-1">Konfirmasi Kata Sandi</label>
            <div class="relative group" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0c5197] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <input id="password_confirmation" x-bind:type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="Masukkan Kata Sandi"
                    class="block w-full pl-12 pr-12 py-3.5 bg-slate-50 border-2 border-slate-200  rounded-xl text-sm font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:border-[#2ee0a7] focus:ring-4 focus:ring-[#2ee0a7]/10 transition-all duration-300">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-[#0c5197] focus:outline-none transition-colors">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 014.182-5.474M9.88 9.88A3 3 0 1014.12 14.12M15 12a3 3 0 00-3-3m-8.5 8.5l15-15" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2 space-y-4">
            <button type="submit" class="w-full flex justify-center items-center gap-3 px-6 py-4 bg-gradient-to-r from-[#03235b] to-[#0c5197] text-white text-sm font-black uppercase tracking-[0.2em] rounded-xl shadow-xl shadow-blue-900/40 hover:from-[#0c5197] hover:to-[#1c88da] focus:ring-4 focus:ring-blue-600/20 active:scale-[0.98] transition-all duration-300">
                Register
                {{-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg> --}}
            </button>
            <div class="text-center">
                <a class="text-xs font-bold text-slate-400 hover:text-[#0c5197] transition-colors uppercase tracking-widest" href="{{ route('login') }}">
                    Sudah punya akun? Log in
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
