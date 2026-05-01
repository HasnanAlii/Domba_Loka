<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Domba Loka - Sistem Manajemen Peternakan</title>

    <!-- Favicon lengkap -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icon/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icon/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/icon/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon/logo.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- TomSelect --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Global Styles --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Sembunyikan ikon sub-menu secara default (saat sidebar terbuka) */
        nav ul i {
            display: none !important;
        }

        @media (min-width: 768px) {
            .sidebar-collapsed nav {
                width: 90px !important;
            }

            .sidebar-collapsed .md\:ml-64 {
                margin-left: 90px !important;
            }

            .sidebar-collapsed nav span,
            .sidebar-collapsed nav p,
            .sidebar-collapsed nav button>svg:not(.collapse-icon) {
                display: none !important;
            }

            .sidebar-collapsed nav .flex-1.flex-col>.px-5>.mt-7 {
                margin-top: 1rem !important;
            }

            .sidebar-collapsed nav .collapse-icon {
                transform: rotate(180deg);
            }

            .sidebar-collapsed nav .justify-between {
                justify-content: center !important;
            }

            .sidebar-collapsed nav .gap-3\.5 {
                gap: 0 !important;
            }

            .sidebar-collapsed nav .px-4 {
                padding-left: 0 !important;
                padding-right: 0 !important;
                justify-content: center !important;
            }

            .sidebar-collapsed nav .px-6 {
                padding-left: 0 !important;
                padding-right: 0 !important;
                flex-direction: column;
                align-items: center;
                justify-content: center !important;
                margin-top: 5px;
            }

            .sidebar-collapsed nav .overflow-hidden {
                display: none !important;
            }

            .sidebar-collapsed nav .w-full.flex.items-center.gap-4.p-4 {
                padding: 0.5rem !important;
                justify-content: center !important;
            }

            .sidebar-collapsed nav .ml-1 {
                margin-left: 0 !important;
                margin-top: 10px;
            }

            /* Show submenus correctly in mini mode */
            .sidebar-collapsed nav ul {
                padding-left: 0 !important;
            }

            .sidebar-collapsed nav ul a {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .sidebar-collapsed nav ul i {
                display: block !important;
                margin: 0 auto !important;
            }
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 transition-all duration-300" x-data="{ showSidebar: false, sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true' }"
    x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebar_collapsed', val))" :class="sidebarCollapsed ? 'sidebar-collapsed' : ''">

    <div class="min-h-screen bg-slate-50">



        {{-- NAVBAR MOBILE --}}
        <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-0 z-50 md:hidden">
            <div class="px-4 h-16 flex items-center justify-between">

                {{-- Tombol Sidebar --}}
                <button @click="showSidebar = true"
                    class="p-3 bg-white border rounded-xl  hover:bg-slate-50 transition">
                    <i data-feather="menu" class="w-6 h-6 text-gray-700"></i>
                </button>

                {{-- Judul Navbar Mobile --}}
                <div class="text-center leading-tight">
                    <h1 class="text-base font-bold text-gray-800">
                        Dashboard Domba Loka
                    </h1>
                </div>

                <a href="{{ route('dashboard') }}" class="p-3 rounded-xl bg-white border hover:bg-slate-50 transition">
                    <i data-feather="home" class="w-5 h-5 text-gray-700"></i>
                </a>

            </div>
        </nav>

        {{-- SIDEBAR --}}
        <div class="md:block" :class="showSidebar ? 'block' : 'hidden'">
            @include('layouts.navigation')
        </div>

        {{-- OVERLAY --}}
        <div x-show="showSidebar" @click="showSidebar=false" class="fixed inset-0 bg-black/40 md:hidden"></div>

        {{-- HEADER DESKTOP --}}
        @isset($header)
            <header class="bg-white shadow md:ml-64 hidden md:block transition-all duration-300">
                <div class="max-w-7xl mx-auto py-6 px-4">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- MAIN CONTENT --}}
        <main>
            <div class="md:ml-64 transition-all duration-300">
                {{ $slot }}
            </div>
        </main>

        {{-- Global Modal --}}
        <x-delete-confirm />

        <!-- Modal Notifikasi Berhasil / Gagal -->
        <div x-data="{
            showModal: false,
            type: '',
            title: '',
            message: '',
            redirectUrl: null
        }"
            x-on:show-notification.window="
                type = $event.detail.type;
                title = $event.detail.title;
                message = $event.detail.message;
                redirectUrl = $event.detail.redirectUrl || null;
                showModal = true;
            "
            style="display: none;" x-show="showModal" class="relative z-[200]" aria-labelledby="modal-title"
            role="dialog" aria-modal="true" x-cloak>

            <!-- Backdrop -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal Panel -->
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div @click.away="showModal = false" x-show="showModal"
                        x-transition:enter="ease-out duration-300 delay-75"
                        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                        class="bg-white rounded-[1rem] shadow-2xl w-full max-w-[400px] p-8 sm:p-10 text-center relative transform transition-all">

                        <!-- Top Logo -->
                        <div class="flex justify-center mb-8">
                            <div class="flex items-center gap-2.5">
                                <x-application-logo class="w-20 h-20 text-[#03235b]" />
                            </div>
                        </div>
                        <!-- Main Icon image -->
                        <div class="flex justify-center mb-6">
                            <template x-if="type === 'success'">
                                <img src="{{ asset('images/icon/succes.png') }}" alt="Success Icon"
                                    class="w-24 h-24 object-contain shadow-sm rounded-full">
                            </template>
                            <template x-if="type === 'error'">
                                <img src="{{ asset('images/icon/danger.png') }}" alt="Error Icon"
                                    class="w-24 h-24 object-contain shadow-sm rounded-full">
                            </template>
                        </div>

                        <!-- Title -->
                        <h3 class="text-3xl font-black text-neutral-900 mb-2" x-text="title"></h3>

                        <!-- Sub Message -->
                        <p class="text-[15px] font-medium text-slate-500 mb-8" x-text="message"></p>

                        <!-- Button Selesai -->
                        <button type="button" @click="showModal = false; if (redirectUrl) { window.location.href = redirectUrl; }"
                            class="w-full py-3.5 text-[15px] font-extrabold text-white transition-all rounded-xl shadow-lg border border-transparent outline-none focus:ring-4"
                            :class="type === 'success' ?
                                'bg-blue-500 hover:bg-blue-700 shadow-blue-500/30 focus:ring-blue-500/30' :
                                'bg-blue-500 hover:bg-blue-600 shadow-blue-500/30 focus:ring-blue-500/30'">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Feather Icons --}}
    <script src="https://unpkg.com/feather-icons"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            feather.replace();
        });
    </script>

    <script>
        window.onload = () => {
            feather.replace();
            if (document.getElementById('preloader')) {
                document.getElementById('preloader').style.display = 'none';
            }
        };
    </script>

    {{-- TomSelect --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    {{-- Alpine + Moment --}}
    <script src="https://unpkg.com/alpinejs" defer></script>

    {{-- Global AJAX Form Submit Interceptor --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[data-ajax-form]').forEach(function (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const submitBtn = form.querySelector('[type="submit"]');
                    const originalText = submitBtn ? submitBtn.innerHTML : '';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<svg class="animate-spin inline w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> Memproses...';
                    }

                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: formData,
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            window.dispatchEvent(new CustomEvent('show-notification', {
                                detail: {
                                    type: 'success',
                                    title: 'Berhasil',
                                    message: data.message || 'Data berhasil disimpan.',
                                    redirectUrl: data.redirect || null,
                                }
                            }));
                        } else {
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                            }
                            const errors = data.errors
                                ? Object.values(data.errors).flat().join('\n')
                                : (data.message || 'Terjadi kesalahan.');
                            window.dispatchEvent(new CustomEvent('show-notification', {
                                detail: {
                                    type: 'error',
                                    title: 'Gagal',
                                    message: errors,
                                    redirectUrl: null,
                                }
                            }));
                        }
                    } catch (err) {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                        console.error(err);
                        window.dispatchEvent(new CustomEvent('show-notification', {
                            detail: {
                                type: 'error',
                                title: 'Gagal',
                                message: 'Terjadi kesalahan jaringan.',
                                redirectUrl: null,
                            }
                        }));
                    }
                });
            });
        });
    </script>

    @isset($scripts)
        {{ $scripts }}
    @endisset

</body>

</html>
