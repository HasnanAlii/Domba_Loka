<div x-data="{
    isOpen: false,
    isLoading: false,
    title: 'Hapus Data?',
    message: 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
    formToSubmit: null,

    init() {
        window.addEventListener('confirm-deletion', (e) => {
            if (this.isLoading) return;
            this.title = e.detail.title || 'Hapus Data?';
            this.message = e.detail.message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
            this.formToSubmit = e.detail.form;
            this.isOpen = true;
        });
    },

    async confirm() {
        if (!this.formToSubmit || this.isLoading) return;
        
        this.isLoading = true;
        
        const form = this.formToSubmit;
        const action = form.action || window.location.href;
        const formData = new FormData(form);
        const method = (form.getAttribute('method') || 'POST').toUpperCase();

        try {
            const response = await fetch(action, {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData,
            });

            const data = await response.json();
            
            if (response.ok && data.success) {
                this.isOpen = false;
                window.dispatchEvent(new CustomEvent('show-notification', {
                    detail: {
                        type: 'success',
                        title: 'Berhasil',
                        message: data.message || 'Data berhasil dihapus.',
                        redirectUrl: data.redirect || window.location.href,
                    }
                }));
            } else {
                 this.isOpen = false;
                 window.dispatchEvent(new CustomEvent('show-notification', {
                    detail: {
                        type: 'error',
                        title: 'Gagal',
                        message: data.message || 'Gagal menghapus data.',
                        redirectUrl: null,
                    }
                }));
            }
        } catch (err) {
            console.error('Delete error:', err);
            this.isOpen = false;
            window.dispatchEvent(new CustomEvent('show-notification', {
                detail: {
                    type: 'error',
                    title: 'Gagal',
                    message: 'Terjadi kesalahan jaringan.',
                    redirectUrl: null,
                }
            }));
        } finally {
            this.isLoading = false;
            this.formToSubmit = null;
        }
    },

    close() {
        if (this.isLoading) return;
        if (this.formToSubmit) {
            delete this.formToSubmit.dataset.confirmed;
        }
        this.isOpen = false;
        this.formToSubmit = null;
    }
}" style="display: none;" x-show="isOpen" class="relative z-[9999]"
    aria-labelledby="delete-modal-title" role="dialog" aria-modal="true" x-cloak>

    <!-- Backdrop -->
    <div x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Panel -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="isOpen" x-transition:enter="ease-out duration-300 delay-75"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="bg-white rounded-[1rem] shadow-2xl w-full max-w-[400px] p-8 sm:p-10 text-center relative transform transition-all">

                <!-- Top Logo -->
                <div class="flex justify-center mb-8">
                    <div class="flex items-center gap-2.5">
                        <x-application-logo class="w-20 h-20 text-[#03235b]" />
                    </div>
                </div>

                <!-- Warning Icon -->
                {{-- <div class="flex justify-center mb-6">
                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-rose-50 text-rose-500 border border-rose-100 shadow-sm">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                </div> --}}

                <!-- Title -->
                <h3 class="text-3xl font-black text-neutral-900 mb-2" id="delete-modal-title" x-text="title"></h3>

                <!-- Message -->
                <p class="text-[15px] font-medium text-slate-500 mb-8" x-text="message"></p>

                <!-- Buttons -->
                <div class="flex flex-col gap-3">
                    <button type="button" @click="confirm()" :disabled="isLoading"
                        class="w-full flex justify-center items-center py-3.5 text-[15px] font-extrabold text-white bg-rose-500 hover:bg-rose-600 transition-all rounded-xl shadow-lg shadow-rose-500/30 border border-transparent outline-none focus:ring-4 focus:ring-rose-500/30 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg x-show="isLoading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display:none;"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="isLoading ? 'Memproses...' : 'Ya, Hapus Permanen'"></span>
                    </button>
                    <button type="button" @click="close()" :disabled="isLoading"
                        class="w-full py-3.5 text-[15px] font-extrabold text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all rounded-xl border border-slate-200 outline-none focus:ring-4 focus:ring-slate-200/50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('submit', (e) => {
        const form = e.target;

        // Skip if already confirmed
        if (form.dataset.confirmed) return;

        const confirmMessage = form.getAttribute('data-confirm-message');
        const method = form.getAttribute('method') || 'GET';
        const isDelete = method.toUpperCase() === 'POST' && form.querySelector(
            'input[name="_method"][value="DELETE"]');

        if (confirmMessage || isDelete) {
            e.preventDefault();

            window.dispatchEvent(new CustomEvent('confirm-deletion', {
                detail: {
                    title: isDelete ? 'Hapus Data?' : 'Konfirmasi Tindakan',
                    message: confirmMessage ||
                        'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
                    form: form
                }
            }));
        }
    });
</script>
