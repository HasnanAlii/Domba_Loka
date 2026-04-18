@if ($page === 'index')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alert = document.getElementById('finance-success-alert');
            const dismissButtons = document.querySelectorAll('[data-dismiss-target]');
            const deleteForms = document.querySelectorAll('form[data-confirm-message]');
            const dateRangeField = document.getElementById('filter_date_range');

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

            if (dateRangeField && typeof flatpickr !== 'undefined') {
                let fp;

                const updateActivePreset = (id) => {
                    document.querySelectorAll('.preset-btn').forEach((btn) => btn.classList.remove('active'));

                    if (id) {
                        const target = document.getElementById(id);
                        if (target) {
                            target.classList.add('active');
                        }
                    }
                };

                const setRange = (start, end, id) => {
                    fp.setDate([start, end]);
                    updateActivePreset(id);
                };

                fp = flatpickr(dateRangeField, {
                    mode: 'range',
                    showMonths: 2,
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd M Y',
                    prevArrow:
                        '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>',
                    nextArrow:
                        '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>',
                    onReady: function (_selectedDates, _dateStr, instance) {
                        const sidebar = document.createElement('div');
                        sidebar.className = 'flatpickr-sidebar';

                        const presets = [
                            { name: 'Hari Ini', id: 'today', range: [new Date(), new Date()] },
                            {
                                name: 'Kemarin',
                                id: 'yesterday',
                                range: [
                                    new Date(Date.now() - 86400000),
                                    new Date(Date.now() - 86400000),
                                ],
                            },
                            {
                                name: '7 Hari Terakhir',
                                id: 'last7',
                                range: [new Date(Date.now() - 6 * 86400000), new Date()],
                            },
                            {
                                name: '30 Hari Terakhir',
                                id: 'last30',
                                range: [new Date(Date.now() - 29 * 86400000), new Date()],
                            },
                            {
                                name: 'Bulan Ini',
                                id: 'thisMonth',
                                range: [
                                    new Date(new Date().getFullYear(), new Date().getMonth(), 1),
                                    new Date(),
                                ],
                            },
                            {
                                name: 'Bulan Kemarin',
                                id: 'lastMonth',
                                range: [
                                    new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1),
                                    new Date(new Date().getFullYear(), new Date().getMonth(), 0),
                                ],
                            },
                            {
                                name: 'Tahun Ini',
                                id: 'thisYear',
                                range: [new Date(new Date().getFullYear(), 0, 1), new Date()],
                            },
                            { name: 'Custom Range', id: 'custom', range: null },
                        ];

                        presets.forEach((preset) => {
                            const btn = document.createElement('div');
                            btn.className = 'preset-btn';
                            btn.id = preset.id;
                            btn.innerText = preset.name;
                            btn.onclick = () => {
                                if (preset.range) {
                                    setRange(preset.range[0], preset.range[1], preset.id);
                                } else {
                                    updateActivePreset(preset.id);
                                }
                            };
                            sidebar.appendChild(btn);
                        });

                        instance.calendarContainer.prepend(sidebar);
                    }
                });
            }
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