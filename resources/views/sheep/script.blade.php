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

        deleteForms.forEach((form) => {
            form.addEventListener('submit', (event) => {
                const message = form.dataset.confirmMessage || 'Yakin ingin menghapus data ini?';

                if (!window.confirm(message)) {
                    event.preventDefault();
                }
            });
        });
    });
</script>