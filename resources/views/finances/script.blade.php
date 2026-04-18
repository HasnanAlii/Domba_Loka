@if ($page === 'index')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alert = document.getElementById('finance-success-alert');
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
@endif

@if ($page === 'form')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const typeField = document.getElementById('type');
            const descriptionField = document.getElementById('description');

            if (!typeField || !descriptionField) {
                return;
            }

            const placeholders = {
                income: 'Contoh: hasil penjualan domba atau pembayaran pelanggan',
                expense: 'Contoh: pembelian pakan, obat, atau biaya operasional',
            };

            const updatePlaceholder = () => {
                descriptionField.placeholder = placeholders[typeField.value] ?? 'Tambahkan deskripsi singkat';
            };

            updatePlaceholder();
            typeField.addEventListener('change', updatePlaceholder);
        });
    </script>
@endif