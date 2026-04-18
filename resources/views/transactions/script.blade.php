<x-slot name="scripts">
    @if(isset($transaction) && isset($sheep))
    @php
        $defaultItems = [['id' => uniqid(), 'sheep_id' => '', 'qty' => 1, 'price' => 0, 'discount' => 0]];
        if (isset($transaction) && $transaction->exists && $transaction->details->count() > 0) {
            $defaultItems = $transaction->details->map(function($d) {
                return [
                    'id' => uniqid(), 
                    'sheep_id' => $d->sheep_id, 
                    'qty' => $d->quantity, 
                    'price' => (float)$d->price, 
                    'discount' => (float)$d->discount
                ];
            })->toArray();
        }
        $alpineItems = old('details', $defaultItems);
    @endphp
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('transactionForm', () => ({
                type: '{{ old('type', $selectedType ?? 'penjualan') }}',
                paymentMethod: '{{ old('payment_method', str_starts_with((string) ($transaction->payment_method ?? ''), 'Transfer Bank') ? 'Transfer Bank' : ($transaction->payment_method ?? 'Tunai')) }}',
                bankAccountId: '{{ old('bank_account_id', $transaction->bank_account_id) }}',
                dbSheep: Object.values(@json($sheep) || {}), // Inject directly
                items: @json($alpineItems),
                
                subtotal: {{ (float)old('subtotal', $transaction->subtotal ?? 0) }},
                tax: {{ (float)old('tax', $transaction->tax ?? 0) }},
                otherFees: {{ (float)old('other_fees', $transaction->other_fees ?? 0) }},
                downpayment: {{ (float)old('downpayment', $transaction->downpayment ?? 0) }},
                total: {{ (float)old('total_price', $transaction->total_price ?? 0) }},
                sisa: {{ (float)old('sisa', $transaction->sisa ?? 0) }},

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
                    if (!this.newSheep.code || !this.newSheep.type_id || !this.newSheep.price || !this.newSheep.weight) {
                        alert('Mohon lengkapi data: Kode, Jenis, Berat, dan Harga Domba!');
                        return;
                    }

                    this.isSavingSheep = true;
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
                                let newItem = { id: Date.now(), sheep_id: data.sheep.id, qty: 1, price: 0, discount: 0 };
                                this.items.push(newItem);
                                this.updatePrice(newItem);
                            }

                            // Close modal & reset form
                            this.isSheepModalOpen = false;
                            this.newSheep = { code: '', type_id: '', weight: '', condition: 'Sehat', price: '' };
                        } else {
                            alert(data.message || 'Gagal menyimpan data domba. Kode mungkin sudah ada.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan jaringan.');
                    } finally {
                        this.isSavingSheep = false;
                    }
                },

                init() {
                    if (this.paymentMethod !== 'Transfer Bank') {
                        this.bankAccountId = '';
                    }

                    this.$watch('paymentMethod', (value) => {
                        if (value !== 'Transfer Bank') {
                            this.bankAccountId = '';
                        }
                    });

                    this.items.forEach(item => {
                        item.price = this.formatDot(item.price);
                    });
                    this.tax = this.formatDot(this.tax);
                    this.otherFees = this.formatDot(this.otherFees);
                    this.downpayment = this.formatDot(this.downpayment);

                    this.calculateTotals();
                },

                updatePrice(item) {
                    let sheep = this.dbSheep.find(s => s.id == item.sheep_id);
                    if (sheep) {
                        item.price = this.formatDot(sheep.price || 2500000); // Provide generic price if undefined
                    }
                    this.calculateTotals();
                },

                addItem() {
                    this.items.push({ id: Date.now(), sheep_id: '', qty: 1, price: 0, discount: 0 });
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
                    this.total = this.subtotal + this.unmaskMoney(this.tax) + this.unmaskMoney(this.otherFees);
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
    </script>
    @endif

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
        });
    </script>
</x-slot>