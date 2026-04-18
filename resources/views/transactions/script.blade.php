<x-slot name="scripts">
    @if (isset($transaction) && isset($sheep))
        @php
            $defaultItems = [['id' => uniqid(), 'sheep_id' => '', 'qty' => 1, 'price' => 0, 'discount' => 0]];
            if (isset($transaction) && $transaction->exists && $transaction->details->count() > 0) {
                $defaultItems = $transaction->details
                    ->map(function ($d) {
                        return [
                            'id' => uniqid(),
                            'sheep_id' => $d->sheep_id,
                            'qty' => $d->quantity,
                            'price' => (float) $d->price,
                            'discount' => (float) $d->discount,
                        ];
                    })
                    ->toArray();
            }
            $alpineItems = old('details', $defaultItems);
        @endphp
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('transactionForm', () => ({
                    type: '{{ old('type', $selectedType ?? 'penjualan') }}',
                    paymentMethod: '{{ old('payment_method', str_starts_with((string) ($transaction->payment_method ?? ''), 'Transfer Bank') ? 'Transfer Bank' : $transaction->payment_method ?? 'Tunai') }}',
                    bankAccountId: '{{ old('bank_account_id', $transaction->bank_account_id) }}',
                    dbSheep: Object.values(@json($sheep) || {}), // Inject directly
                    items: @json($alpineItems),

                    subtotal: {{ (float) old('subtotal', $transaction->subtotal ?? 0) }},
                    tax: {{ (float) old('tax', $transaction->tax ?? 0) }},
                    taxNominal: 0,
                    otherFees: {{ (float) old('other_fees', $transaction->other_fees ?? 0) }},
                    downpayment: {{ (float) old('downpayment', $transaction->downpayment ?? 0) }},
                    total: {{ (float) old('total_price', $transaction->total_price ?? 0) }},
                    sisa: {{ (float) old('sisa', $transaction->sisa ?? 0) }},

                    // Modal state
                    isSheepModalOpen: false,
                    isSavingSheep: false,
                    newSheep: {
                        code: '',
                        type_id: '',
                        weight: '',
                        condition: 'Sehat',
                        price: ''
                    },

                    async saveNewSheep() {
                        if (!this.newSheep.code || !this.newSheep.type_id || !this.newSheep.price || !
                            this.newSheep.weight) {
                            alert('Mohon lengkapi data: Kode, Jenis, Berat, dan Harga Domba!');
                            return;
                        }

                        this.isSavingSheep = true;
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content');
                            const response = await fetch('{{ route('sheep.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify(this.newSheep)
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                // Add to dbSheep array so it appears in dropdown immediately
                                this.dbSheep.push(data.sheep);

                                // Find empty item row or create a new one to auto-select
                                let emptyItem = this.items.find(i => !i.sheep_id);
                                if (emptyItem) {
                                    emptyItem.sheep_id = data.sheep.id;
                                    this.updatePrice(emptyItem);
                                } else {
                                    let newItem = {
                                        id: Date.now(),
                                        sheep_id: data.sheep.id,
                                        qty: 1,
                                        price: 0,
                                        discount: 0
                                    };
                                    this.items.push(newItem);
                                    this.updatePrice(newItem);
                                }

                                // Close modal & reset form
                                this.isSheepModalOpen = false;
                                this.newSheep = {
                                    code: '',
                                    type_id: '',
                                    weight: '',
                                    condition: 'Sehat',
                                    price: ''
                                };
                            } else {
                                alert(data.message ||
                                    'Gagal menyimpan data domba. Kode mungkin sudah ada.');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan jaringan.');
                        } finally {
                            this.isSavingSheep = false;
                        }
                    },

                    init() {
                        // Identify Cash Account ID
                        const cashAccountId = String(@json($bankAccounts->where('bank_name', 'Kas Tunai')->first()?->id) || '');

                        this.$nextTick(() => {
                            if (this.paymentMethod === 'Tunai' && cashAccountId) {
                                this.bankAccountId = cashAccountId;
                            }
                        });

                        this.$watch('paymentMethod', (value) => {
                            if (value === 'Tunai' && cashAccountId) {
                                this.bankAccountId = cashAccountId;
                            } else if (value !== 'Transfer Bank') {
                                this.bankAccountId = '';
                            }
                        });

                        this.items.forEach(item => {
                            this.updatePrice(item);
                        });
                        
                        // Pajak sekarang persen, tidak perlu formatDot
                        this.otherFees = this.formatDot(this.otherFees);
                        this.downpayment = this.formatDot(this.downpayment);

                        this.calculateTotals();
                    },

                    updatePrice(item) {
                        let sheep = this.dbSheep.find(s => s.id == item.sheep_id);
                        item.price = sheep ? this.formatDot(sheep.price) : '';
                        this.calculateTotals();
                    },

                    addItem() {
                        this.items.push({
                            id: Date.now(),
                            sheep_id: '',
                            qty: 1,
                            price: 0,
                            discount: 0
                        });
                        this.calculateTotals();
                    },

                    removeItem(index) {
                        if (this.items.length > 1) {
                            this.items.splice(index, 1);
                            this.calculateTotals();
                        }
                    },

                    unmaskMoney(val) {
                        if (val === null || val === undefined || val === '') return 0;
                        return parseFloat(String(val).replace(/\./g, '')) || 0;
                    },

                    formatDot(val) {
                        if (val === null || val === undefined || val === '') return '';
                        let num = parseFloat(String(val).replace(/\./g, '')) || 0;
                        return num.toLocaleString('id-ID');
                    },

                    itemTotal(item) {
                        let total = ((item.qty || 0) * this.unmaskMoney(item.price));
                        if (item.discount > 0) {
                            total -= (total * (item.discount / 100));
                        }
                        return total;
                    },

                    calculateTotals() {
                        this.subtotal = this.items.reduce((sum, item) => sum + this.itemTotal(item), 0);
                        
                        // Hitung pajak dari persentase
                        const taxPercent = parseFloat(this.tax) || 0;
                        this.taxNominal = (this.subtotal * taxPercent) / 100;
                        
                        this.total = this.subtotal + this.taxNominal + this.unmaskMoney(this.otherFees);
                        this.sisa = this.total - this.unmaskMoney(this.downpayment);
                    },

                    formatMoney(amount) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(amount || 0);
                    }
                }));
            });
            document.addEventListener('DOMContentLoaded', () => {
            // Confirmation form handlers
            const forms = document.querySelectorAll('form[data-confirm-message]');
            forms.forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!window.confirm(form.dataset.confirmMessage)) {
                        event.preventDefault();
                    }
                });
            });

            // Transaction Type Sync (Sales vs Purchase)
            const typeSelect = document.querySelector('#type');
            const customerField = document.querySelector('[data-party-field="customer"]');
            const supplierField = document.querySelector('[data-party-field="supplier"]');
            const customerSelect = document.querySelector('#customer_id');
            const supplierSelect = document.querySelector('#supplier_id');

            if (typeSelect && customerField && supplierField && customerSelect && supplierSelect) {
                const syncPartyFields = () => {
                    if (typeSelect.value === 'penjualan') {
                        customerField.classList.remove('hidden');
                        supplierField.classList.add('hidden');
                        customerSelect.disabled = false;
                        supplierSelect.disabled = true;
                        supplierSelect.value = '';
                    } else if (typeSelect.value === 'pembelian') {
                        supplierField.classList.remove('hidden');
                        customerField.classList.add('hidden');
                        supplierSelect.disabled = false;
                        customerSelect.disabled = true;
                        customerSelect.value = '';
                    }
                };
                typeSelect.addEventListener('change', syncPartyFields);
                syncPartyFields();
            }

            // Date Range Picker Initialization (Only if on Filter page)
            const dateRangeField = document.getElementById('filter_date_range');
            if (dateRangeField) {
                let fp;
                const updateActivePreset = (id) => {
                    document.querySelectorAll('.preset-btn').forEach(btn => btn.classList.remove('active'));
                    if (id) {
                        const target = document.getElementById(id);
                        if (target) target.classList.add('active');
                    }
                };

                const setRange = (start, end, id) => {
                    fp.setDate([start, end]);
                    updateActivePreset(id);
                };

                fp = flatpickr(dateRangeField, {
                    mode: "range",
                    showMonths: 2,
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d M Y",
                    prevArrow: '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>',
                    nextArrow: '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>',
                    onReady: function(selectedDates, dateStr, instance) {
                        const sidebar = document.createElement('div');
                        sidebar.className = 'flatpickr-sidebar';
                        
                        const presets = [
                            { name: 'Hari Ini', id: 'today', range: [new Date(), new Date()] },
                            { name: 'Kemarin', id: 'yesterday', range: [new Date(Date.now() - 86400000), new Date(Date.now() - 86400000)] },
                            { name: '7 Hari Terakhir', id: 'last7', range: [new Date(Date.now() - 6 * 86400000), new Date()] },
                            { name: '30 Hari Terakhir', id: 'last30', range: [new Date(Date.now() - 29 * 86400000), new Date()] },
                            { name: 'Bulan Ini', id: 'thisMonth', range: [new Date(new Date().getFullYear(), new Date().getMonth(), 1), new Date()] },
                            { name: 'Bulan Kemarin', id: 'lastMonth', range: [new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1), new Date(new Date().getFullYear(), new Date().getMonth(), 0)] },
                            { name: 'Tahun Ini', id: 'thisYear', range: [new Date(new Date().getFullYear(), 0, 1), new Date()] },
                            { name: 'Custom Range', id: 'custom', range: null }
                        ];

                        presets.forEach(p => {
                            const btn = document.createElement('div');
                            btn.className = 'preset-btn';
                            btn.id = p.id;
                            btn.innerText = p.name;
                            btn.onclick = () => {
                                if (p.range) {
                                    setRange(p.range[0], p.range[1], p.id);
                                } else {
                                    updateActivePreset(p.id);
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
</x-slot>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let fp;

            const updateActivePreset = (id) => {
                document.querySelectorAll('.preset-btn').forEach(btn => btn.classList.remove('active'));
                if (id) document.getElementById(id).classList.add('active');
            };

            const setRange = (start, end, id) => {
                fp.setDate([start, end]);
                updateActivePreset(id);
            };

            fp = flatpickr("#filter_date_range", {
                mode: "range",
                showMonths: 2,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y",
                prevArrow: '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>',
                nextArrow: '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>',
                onReady: function(selectedDates, dateStr, instance) {
                    // Create Sidebar
                    const sidebar = document.createElement('div');
                    sidebar.className = 'flatpickr-sidebar';
                    
                    const presets = [
                        { name: 'Hari Ini', id: 'today', range: [new Date(), new Date()] },
                        { name: 'Kemarin', id: 'yesterday', range: [new Date(Date.now() - 86400000), new Date(Date.now() - 86400000)] },
                        { name: '7 Hari Terakhir', id: 'last7', range: [new Date(Date.now() - 6 * 86400000), new Date()] },
                        { name: '30 Hari Terakhir', id: 'last30', range: [new Date(Date.now() - 29 * 86400000), new Date()] },
                        { name: 'Bulan Ini', id: 'thisMonth', range: [new Date(new Date().getFullYear(), new Date().getMonth(), 1), new Date()] },
                        { name: 'Bulan Kemarin', id: 'lastMonth', range: [new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1), new Date(new Date().getFullYear(), new Date().getMonth(), 0)] },
                        { name: 'Tahun Ini', id: 'thisYear', range: [new Date(new Date().getFullYear(), 0, 1), new Date()] },
                        { name: 'Custom Range', id: 'custom', range: null }
                    ];

                    presets.forEach(p => {
                        const btn = document.createElement('div');
                        btn.className = 'preset-btn';
                        btn.id = p.id;
                        btn.innerText = p.name;
                        btn.onclick = () => {
                            if (p.range) {
                                setRange(p.range[0], p.range[1], p.id);
                            } else {
                                updateActivePreset(p.id);
                            }
                        };
                        sidebar.appendChild(btn);
                    });

                    instance.calendarContainer.prepend(sidebar);
                }
            });
        });
    </script>