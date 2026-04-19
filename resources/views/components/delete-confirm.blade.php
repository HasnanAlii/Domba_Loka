<div x-data="{ 
    isOpen: false, 
    title: 'Konfirmasi Hapus',
    message: 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
    formToSubmit: null,

    init() {
        window.addEventListener('confirm-deletion', (e) => {
            this.title = e.detail.title || 'Konfirmasi Hapus';
            this.message = e.detail.message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
            this.formToSubmit = e.detail.form;
            this.isOpen = true;
        });
    },

    confirm() {
        if (this.formToSubmit) {
            this.formToSubmit.dataset.confirmed = 'true';
            this.formToSubmit.submit();
        }
        this.isOpen = false;
    },

    close() {
        if (this.formToSubmit) {
            delete this.formToSubmit.dataset.confirmed;
        }
        this.isOpen = false;
        this.formToSubmit = null;
    }
}" 
x-show="isOpen" 
x-cloak 
class="fixed inset-0 z-[9999] overflow-y-auto" 
style="display: none;"
aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md">
            
            <div class="bg-white p-8">
                <div class="text-center">
                    <!-- Warning Icon Area -->
                    <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-3xl bg-rose-50 text-rose-500 shadow-sm border border-rose-100/50">
                        <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl font-black tracking-tight text-slate-800 mb-2" id="modal-title" x-text="title"></h3>
                    <div class="mt-2">
                        <p class="text-[14px] font-medium leading-relaxed text-slate-500" x-text="message"></p>
                    </div>
                </div>

                <div class="mt-10 flex flex-col gap-3">
                    <button type="button" 
                            @click="confirm()" 
                            class="inline-flex w-full justify-center rounded-2xl bg-blue-600 px-6 py-4 text-xs font-black uppercase tracking-[0.2em] text-white shadow-xl shadow-slate-200 transition-all hover:bg-blue-700 hover:shadow-blue-500/30 active:scale-[0.98]">
                        Ya, Hapus Permanen
                    </button>
                    <button type="button" 
                            @click="close()" 
                            class="inline-flex w-full justify-center rounded-2xl border border-slate-100 bg-slate-50/50 px-6 py-4 text-xs font-black uppercase tracking-[0.2em] text-slate-400 transition-all hover:bg-white hover:text-slate-600 active:scale-[0.98]">
                        Batalkan
                    </button>
                </div>
            </div>

            <!-- Absolute decorative elements -->
            <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-slate-50/50 blur-2xl"></div>
            <div class="absolute -left-4 -bottom-4 h-24 w-24 rounded-full bg-rose-50/30 blur-2xl"></div>
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
        const isDelete = method.toUpperCase() === 'POST' && form.querySelector('input[name="_method"][value="DELETE"]');

        if (confirmMessage || isDelete) {
            e.preventDefault();
            
            window.dispatchEvent(new CustomEvent('confirm-deletion', {
                detail: {
                    title: isDelete ? 'Hapus Data?' : 'Konfirmasi Tindakan',
                    message: confirmMessage || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
                    form: form
                }
            }));
        }
    });
</script>
