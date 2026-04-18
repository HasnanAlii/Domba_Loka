<script>
    document.addEventListener('DOMContentLoaded', () => {
        const forms = document.querySelectorAll('form[data-confirm-message]');
        forms.forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!window.confirm(form.dataset.confirmMessage)) {
                    event.preventDefault();
                }
            });
        });
    });
</script>
