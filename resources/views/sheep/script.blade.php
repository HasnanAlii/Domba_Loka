<script>
    document.addEventListener('DOMContentLoaded', () => {
        const alert = document.querySelector('[data-auto-dismiss]');
        const dismissButtons = document.querySelectorAll('[data-dismiss-target]');
        const deleteForms = document.querySelectorAll('form[data-confirm-message]');

        if (alert) {
            setTimeout(() => {
                alert.remove();
            }, 4000);
        }

        dismissButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const target = document.querySelector(button.dataset.dismissTarget);

                if (target) {
                    target.remove();
                }
            });
        });

        // Sync accumulated gallery files to file input before form submit
        const form = document.querySelector('form[enctype="multipart/form-data"]');
        if (form) {
            form.addEventListener('submit', () => {
                const galleryEl = document.querySelector('[x-ref="galleryInput"]');
                if (!galleryEl) return;

                const alpineComp = Alpine.$data(galleryEl.closest('[x-data]'));
                if (!alpineComp || !alpineComp.files || alpineComp.files.length === 0) return;

                const dt = new DataTransfer();
                alpineComp.files.forEach(f => dt.items.add(f));
                galleryEl.files = dt.files;
            });
        }
    });
</script>