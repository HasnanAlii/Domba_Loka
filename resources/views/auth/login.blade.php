<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-2xl font-black tracking-tight text-slate-800">Selamat Datang</h2>
        <p class="mt-2 text-sm font-medium text-slate-500">Silakan masuk ke akun Domba Loka Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-400 ml-1">Email Resmi</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0c5197] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </div>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       placeholder="Masukkan Email"
                       class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:border-[#2ee0a7] focus:ring-4 focus:ring-[#2ee0a7]/10 transition-all duration-300">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="flex justify-between items-center px-1">
                <label for="password" class="text-[11px] font-black uppercase tracking-[0.15em] text-slate-400">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-[11px] font-bold text-[#0c5197] hover:text-[#03235b] transition-colors uppercase tracking-wider" href="{{ route('password.request') }}">
                        Lupa Sandi?
                    </a>
                @endif
            </div>
            <div class="relative group" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#0c5197] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" 
                       x-bind:type="show ? 'text' : 'password'" 
                       name="password" 
                       required 
                       placeholder="Masukkan Kata Sandi"
                       class="block w-full pl-12 pr-12 py-4 bg-slate-50 border-2 border-slate-200  rounded-xl text-sm font-bold text-slate-700 placeholder-slate-400 focus:bg-white focus:border-[#2ee0a7] focus:ring-4 focus:ring-[#2ee0a7]/10 transition-all duration-300">
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
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between px-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <div class="relative">
                    <input id="remember_me" type="checkbox" name="remember" class="sr-only">
                    <div class="w-10 h-6 bg-slate-200 rounded-full shadow-inner transition-colors group-has-[:checked]:bg-[#2ee0a7]"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow transition-transform group-has-[:checked]:translate-x-4"></div>
                </div>
                <span class="ms-3 text-xs font-bold text-slate-500 group-hover:text-slate-700 transition-colors">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="space-y-4">
            <button type="submit" class="w-full flex justify-center items-center gap-3 px-6 py-4 bg-gradient-to-r from-[#03235b] to-[#0c5197] text-white text-sm font-black uppercase tracking-[0.2em] rounded-xl shadow-xl shadow-blue-900/40 hover:from-[#0c5197] hover:to-[#1c88da] focus:ring-4 focus:ring-blue-600/20 active:scale-[0.98] transition-all duration-300">
                Log In
                {{-- <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg> --}}
            </button>

            <div class="text-center">
                <a class="text-xs font-bold text-slate-400 hover:text-[#0c5197] transition-colors uppercase tracking-widest" href="{{ route('register') }}">
                    Belum punya akun? Daftar
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
