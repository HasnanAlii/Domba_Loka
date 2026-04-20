<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\Finance;
use App\Models\Sheep;
use App\Models\SheepType;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $dateRange = $request->query('date_range');
        $selectedType = $this->resolveType($request->query('type'));
        $referenceNumber = trim((string) $request->query('reference_number', ''));
        $sheepId = $request->query('sheep_id');
        $customerId = $request->query('customer_id');
        $supplierId = $request->query('supplier_id');
        $totalPriceInput = $request->query('total_price');
        $totalPrice = is_numeric($totalPriceInput) ? (float) $totalPriceInput : null;

        $filters = [
            'date_range' => $dateRange,
            'type' => $selectedType,
            'total_price' => $totalPriceInput,
            'reference_number' => $referenceNumber,
            'sheep_id' => $sheepId,
            'customer_id' => $customerId,
            'supplier_id' => $supplierId,
        ];

        $transactions = Transaction::with(['customer', 'supplier', 'details.sheep'])
            ->when($dateRange, function ($query) use ($dateRange) {
                if (str_contains($dateRange, ' to ')) {
                    [$start, $end] = explode(' to ', $dateRange);
                    $query->whereBetween('created_at', [
                        $start.' 00:00:00',
                        $end.' 23:59:59',
                    ]);
                } else {
                    $query->whereDate('created_at', $dateRange);
                }
            })
            ->when($selectedType !== null, function ($query) use ($selectedType) {
                $query->where('type', $selectedType);
            })
            ->when($totalPrice !== null, function ($query) use ($totalPrice) {
                $query->where('total_price', $totalPrice);
            })
            ->when($referenceNumber !== '', function ($query) use ($referenceNumber) {
                $query->where('reference_number', 'like', "%{$referenceNumber}%");
            })
            ->when($sheepId, function ($query) use ($sheepId) {
                $query->whereHas('details', function ($q) use ($sheepId) {
                    $q->where('sheep_id', $sheepId);
                });
            })
            ->when($customerId, function ($query) use ($customerId) {
                $query->where('customer_id', $customerId);
            })
            ->when($supplierId, function ($query) use ($supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $sheepOptions = $this->sheep(false);
        $customerOptions = $this->customers();
        $supplierOptions = $this->suppliers();

        return view('transactions.index', compact('transactions', 'selectedType', 'filters', 'sheepOptions', 'customerOptions', 'supplierOptions'));
    }

    public function create(Request $request): View
    {
        $selectedType = $this->resolveType($request->query('type')) ?? 'penjualan';

        return $this->formView(
            transaction: new Transaction,
            pageTitle: 'Tambah Transaksi',
            submitLabel: 'Simpan Transaksi',
            action: route('transactions.store'),
            selectedType: $selectedType,
        );
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->merge([
            'tax' => str_replace('.', '', $request->input('tax', '0') ?: '0'),
            'other_fees' => str_replace('.', '', $request->input('other_fees', '0') ?: '0'),
            'downpayment' => str_replace('.', '', $request->input('downpayment', '0') ?: '0'),
        ]);
        if ($request->has('details')) {
            $details = $request->input('details');
            foreach ($details as $key => $detail) {
                if (isset($detail['price'])) {
                    $details[$key]['price'] = str_replace('.', '', $detail['price'] ?: '0');
                }
            }
            $request->merge(['details' => $details]);
        }

        $validated = $request->validate([
            'type' => ['required', Rule::in(['penjualan', 'pembelian'])],
            'kasir' => ['required', 'string', 'max:255'],
            'customer_id' => [
                'nullable',
                'exists:customers,id',
                Rule::requiredIf(fn () => $request->input('type') === 'penjualan'),
                Rule::prohibitedIf(fn () => $request->input('type') === 'pembelian'),
            ],
            'supplier_id' => [
                'nullable',
                'exists:suppliers,id',
                Rule::requiredIf(fn () => $request->input('type') === 'pembelian'),
                Rule::prohibitedIf(fn () => $request->input('type') === 'penjualan'),
            ],
            'bank_account_id' => [
                'nullable',
                'exists:bank_accounts,id',
                Rule::requiredIf(fn () => $request->input('payment_method') === 'Transfer Bank'),
            ],
            'payment_method' => ['required', 'string', 'max:255'],
            'attachment' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'reference_number' => ['nullable', 'string', 'max:255', 'unique:transactions,reference_number'],
            'transaction_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'warehouse' => ['required', 'string', 'max:255'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'other_fees' => ['nullable', 'numeric', 'min:0'],
            'downpayment' => ['nullable', 'numeric', 'min:0'],

            // New details validation
            'details' => ['required', 'array', 'min:1'],
            'details.*.sheep_id' => ['required', 'exists:sheep,id'],
            'details.*.quantity' => ['required', 'numeric', 'min:1'],
            'details.*.price' => ['required', 'numeric', 'min:0'],
            'details.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        if (empty($validated['reference_number'])) {
            $validated['reference_number'] = 'SJ-'.date('Ymd').'-'.strtoupper(Str::random(4));
        }

        $validated['bank_account_id'] = $this->resolveBankAccountId(
            paymentMethod: $validated['payment_method'],
            bankAccountId: $validated['bank_account_id'] ?? null,
        );

        $subtotal = 0;
        $detailsData = [];
        foreach ($validated['details'] as $detail) {
            $qty = $detail['quantity'];
            $price = $detail['price'];
            $discount = $detail['discount'] ?? 0;

            $lineTotal = ($qty * $price);
            if ($discount > 0) {
                $lineTotal -= $lineTotal * ($discount / 100);
            }
            $subtotal += $lineTotal;

            $detailsData[] = [
                'sheep_id' => $detail['sheep_id'],
                'quantity' => $qty,
                'price' => $price,
                'discount' => $discount,
                'total_price' => $lineTotal,
            ];
        }

        $tax = (float) ($validated['tax'] ?? 0);
        $otherFees = (float) ($validated['other_fees'] ?? 0);
        $globalDiscount = 0;
        $downpayment = (float) ($validated['downpayment'] ?? 0);

        $total = $subtotal + $tax + $otherFees - $globalDiscount;
        $sisa = max(0, $total - $downpayment);

        $attachmentPath = $request->hasFile('attachment')
            ? $request->file('attachment')->store('attachments/transactions', 'public')
            : null;

        $transaction = DB::transaction(function () use ($validated, $subtotal, $total, $tax, $otherFees, $globalDiscount, $downpayment, $sisa, $detailsData, $attachmentPath) {
            $transaction = Transaction::create([
                'type' => $validated['type'],
                'kasir' => $validated['kasir'],
                'customer_id' => $validated['customer_id'] ?? null,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'bank_account_id' => $validated['bank_account_id'] ?? null,
                'payment_method' => $validated['payment_method'],
                'attachment' => $attachmentPath,
                'reference_number' => $validated['reference_number'],
                'subtotal' => $subtotal,
                'total_price' => $total,
                'tax' => $tax,
                'other_fees' => $otherFees,
                'discount' => $globalDiscount,
                'downpayment' => $downpayment,
                'sisa' => $sisa,
                'transaction_date' => $validated['transaction_date'],
                'due_date' => $validated['due_date'],
                'warehouse' => $validated['warehouse'],
            ]);

            $transaction->details()->createMany($detailsData);

            $sheepIds = collect($detailsData)->pluck('sheep_id');
            if ($validated['type'] === 'penjualan') {
                Sheep::whereIn('id', $sheepIds)->update(['status' => 'terjual']);
            } elseif ($validated['type'] === 'pembelian') {
                Sheep::whereIn('id', $sheepIds)->update(['status' => 'tersedia']);
            }

            $this->applyBankAccountSaldo(
                bankAccountId: $validated['bank_account_id'] ?? null,
                transactionType: $validated['type'],
                totalAmount: $total,
            );

            $this->syncFinanceFromTransaction(
                transactionId: $transaction->id,
                transactionType: $validated['type'],
                totalAmount: $total,
                bankAccountId: $validated['bank_account_id'],
                referenceNumber: $validated['reference_number'],
            );

            return $transaction;
        });

        return $request->wantsJson()
            ? response()->json(['success' => true, 'message' => 'Transaksi berhasil ditambahkan.', 'redirect' => route('transactions.show', $transaction->id)])
            : redirect()->route('transactions.show', $transaction->id)->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show(Transaction $transaction): View
    {
        $transaction->load(['customer', 'supplier', 'details.sheep', 'finances']);

        return view('transactions.show', compact('transaction'));
    }

    public function exportPDF(Transaction $transaction)
    {
        $transaction->load(['customer', 'supplier', 'details.sheep.sheepType', 'bankAccount']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('transactions.export.pdf', compact('transaction'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Faktur-' . $transaction->reference_number . '.pdf');
    }

    public function edit(Request $request, Transaction $transaction): View
    {
        $selectedType = $this->resolveType($request->query('type')) ?? $transaction->type;

        return $this->formView(
            transaction: $transaction,
            pageTitle: 'Edit Transaksi',
            submitLabel: 'Perbarui Transaksi',
            action: route('transactions.update', $transaction),
            method: 'PUT',
            selectedType: $selectedType,
        );
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse|JsonResponse
    {
        $request->merge([
            'tax' => str_replace('.', '', $request->input('tax', '0') ?: '0'),
            'other_fees' => str_replace('.', '', $request->input('other_fees', '0') ?: '0'),
            'downpayment' => str_replace('.', '', $request->input('downpayment', '0') ?: '0'),
        ]);
        if ($request->has('details')) {
            $details = $request->input('details');
            foreach ($details as $key => $detail) {
                if (isset($detail['price'])) {
                    $details[$key]['price'] = str_replace('.', '', $detail['price'] ?: '0');
                }
            }
            $request->merge(['details' => $details]);
        }

        $validated = $request->validate([
            'type' => ['required', Rule::in(['penjualan', 'pembelian'])],
            'kasir' => ['required', 'string', 'max:255'],
            'customer_id' => [
                'nullable',
                'exists:customers,id',
                Rule::requiredIf(fn () => $request->input('type') === 'penjualan'),
                Rule::prohibitedIf(fn () => $request->input('type') === 'pembelian'),
            ],
            'supplier_id' => [
                'nullable',
                'exists:suppliers,id',
                Rule::requiredIf(fn () => $request->input('type') === 'pembelian'),
                Rule::prohibitedIf(fn () => $request->input('type') === 'penjualan'),
            ],
            'bank_account_id' => [
                'nullable',
                'exists:bank_accounts,id',
                Rule::requiredIf(fn () => $request->input('payment_method') === 'Transfer Bank'),
            ],
            'payment_method' => ['required', 'string', 'max:255'],
            'attachment' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'reference_number' => ['nullable', 'string', 'max:255', 'unique:transactions,reference_number,'.$transaction->id],
            'transaction_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'warehouse' => ['required', 'string', 'max:255'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'other_fees' => ['nullable', 'numeric', 'min:0'],
            'downpayment' => ['nullable', 'numeric', 'min:0'],

            'details' => ['required', 'array', 'min:1'],
            'details.*.sheep_id' => ['required', 'exists:sheep,id'],
            'details.*.quantity' => ['required', 'numeric', 'min:1'],
            'details.*.price' => ['required', 'numeric', 'min:0'],
            'details.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        if (empty($validated['reference_number'])) {
            $validated['reference_number'] = $transaction->reference_number ?: 'SJ-'.date('Ymd').'-'.strtoupper(Str::random(4));
        }

        $validated['bank_account_id'] = $this->resolveBankAccountId(
            paymentMethod: $validated['payment_method'],
            bankAccountId: $validated['bank_account_id'] ?? null,
        );

        $subtotal = 0;
        $detailsData = [];
        foreach ($validated['details'] as $detail) {
            $qty = $detail['quantity'];
            $price = $detail['price'];
            $discount = $detail['discount'] ?? 0;

            $lineTotal = ($qty * $price);
            if ($discount > 0) {
                $lineTotal -= $lineTotal * ($discount / 100);
            }
            $subtotal += $lineTotal;

            $detailsData[] = [
                'sheep_id' => $detail['sheep_id'],
                'quantity' => $qty,
                'price' => $price,
                'discount' => $discount,
                'total_price' => $lineTotal,
            ];
        }

        $tax = (float) ($validated['tax'] ?? 0);
        $otherFees = (float) ($validated['other_fees'] ?? 0);
        $globalDiscount = $transaction->discount;
        $downpayment = (float) ($validated['downpayment'] ?? 0);

        $total = $subtotal + $tax + $otherFees - $globalDiscount;
        $sisa = max(0, $total - $downpayment);

        // Handle file upload before transaction to avoid partial writes
        $newAttachmentPath = $transaction->attachment; // keep old by default
        if ($request->hasFile('attachment')) {
            if ($transaction->attachment) {
                Storage::disk('public')->delete($transaction->attachment);
            }
            $newAttachmentPath = $request->file('attachment')->store('attachments/transactions', 'public');
        }

        DB::transaction(function () use ($transaction, $validated, $subtotal, $total, $tax, $otherFees, $downpayment, $sisa, $detailsData, $newAttachmentPath) {
            $oldType = $transaction->type;
            $oldTotal = (float) $transaction->total_price;
            $oldBankAccountId = $transaction->bank_account_id;
            $oldSheepIds = $transaction->details()->pluck('sheep_id');

            if ($oldType === 'penjualan') {
                Sheep::whereIn('id', $oldSheepIds)->update(['status' => 'tersedia']);
            }

            $this->revertBankAccountSaldo(
                bankAccountId: $oldBankAccountId,
                transactionType: $oldType,
                totalAmount: $oldTotal,
            );

            $transaction->update([
                'type' => $validated['type'],
                'kasir' => $validated['kasir'],
                'customer_id' => $validated['customer_id'] ?? null,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'bank_account_id' => $validated['bank_account_id'] ?? null,
                'payment_method' => $validated['payment_method'],
                'attachment' => $newAttachmentPath,
                'reference_number' => $validated['reference_number'],
                'subtotal' => $subtotal,
                'total_price' => $total,
                'tax' => $tax,
                'other_fees' => $otherFees,
                'downpayment' => $downpayment,
                'sisa' => $sisa,
                'transaction_date' => $validated['transaction_date'],
                'due_date' => $validated['due_date'],
                'warehouse' => $validated['warehouse'],
            ]);

            $transaction->details()->delete();
            $transaction->details()->createMany($detailsData);

            $newSheepIds = collect($detailsData)->pluck('sheep_id');
            if ($validated['type'] === 'penjualan') {
                Sheep::whereIn('id', $newSheepIds)->update(['status' => 'terjual']);
            } elseif ($validated['type'] === 'pembelian') {
                Sheep::whereIn('id', $newSheepIds)->update(['status' => 'tersedia']);
            }

            $this->applyBankAccountSaldo(
                bankAccountId: $validated['bank_account_id'] ?? null,
                transactionType: $validated['type'],
                totalAmount: $total,
            );

            $this->syncFinanceFromTransaction(
                transactionId: $transaction->id,
                transactionType: $validated['type'],
                totalAmount: $total,
                bankAccountId: $validated['bank_account_id'],
                referenceNumber: $validated['reference_number'],
            );
        });

        return $request->wantsJson()
            ? response()->json(['success' => true, 'message' => 'Transaksi berhasil diperbarui.', 'redirect' => route('transactions.show', $transaction->id)])
            : redirect()->route('transactions.show', $transaction->id)->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction): RedirectResponse|JsonResponse
    {
        $type = $transaction->type;

        DB::transaction(function () use ($transaction, $type) {
            if ($type === 'penjualan') {
                $sheepIds = $transaction->details()->pluck('sheep_id');
                Sheep::whereIn('id', $sheepIds)->update(['status' => 'tersedia']);
            }

            $this->revertBankAccountSaldo(
                bankAccountId: $transaction->bank_account_id,
                transactionType: $transaction->type,
                totalAmount: (float) $transaction->total_price,
            );

            $transaction->finances()->delete();

            $transaction->delete();
        });

        return request()->wantsJson()
            ? response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus.', 'redirect' => route('transactions.index', ['type' => $type])])
            : redirect()->route('transactions.index', ['type' => $type])->with('success', 'Transaksi berhasil dihapus.');
    }

    private function formView(
        Transaction $transaction,
        string $pageTitle,
        string $submitLabel,
        string $action,
        string $method = 'POST',
        ?string $selectedType = null,
    ): View {
        $resolvedType = old('type', $transaction->type ?? $selectedType ?? 'penjualan');

        return view('transactions.form', [
            'transaction' => $transaction,
            'pageTitle' => $pageTitle,
            'submitLabel' => $submitLabel,
            'action' => $action,
            'method' => $method,
            'selectedType' => $resolvedType,
            'customers' => $this->customers(),
            'suppliers' => $this->suppliers(),
            'sheep' => $this->sheep(true, $transaction),
            'bankAccounts' => $this->bankAccounts(),
            'sheepTypes' => SheepType::orderBy('name')->get(),
        ]);
    }

    private function resolveType(?string $type): ?string
    {
        return in_array($type, ['penjualan', 'pembelian'], true) ? $type : null;
    }

    private function customers(): Collection
    {
        return Customer::orderBy('name')->get();
    }

    private function suppliers(): Collection
    {
        return Supplier::orderBy('name')->get();
    }

    private function sheep(bool $onlyAvailable = false, ?Transaction $transaction = null): Collection
    {
        $query = Sheep::with('sheepType')->orderBy('code');

        if ($onlyAvailable) {
            $query->where(function ($q) use ($transaction) {
                // Return 'tersedia' sheep...
                $q->where('status', 'tersedia');

                // OR sheep already attached to this very transaction (so they aren't missing when editing)
                if ($transaction && $transaction->exists) {
                    $attachedSheepIds = $transaction->details()->pluck('sheep_id');
                    if ($attachedSheepIds->isNotEmpty()) {
                        $q->orWhereIn('id', $attachedSheepIds);
                    }
                }
            });
        }

        return $query->get();
    }

    private function bankAccounts(): Collection
    {
        return BankAccount::orderBy('bank_name')->get();
    }

    private function resolveBankAccountId(string $paymentMethod, ?int $bankAccountId): int
    {
        if ($paymentMethod === 'Transfer Bank') {
            if ($bankAccountId === null) {
                throw ValidationException::withMessages([
                    'bank_account_id' => 'Rekening bank wajib dipilih untuk pembayaran transfer.',
                ]);
            }

            return $bankAccountId;
        }

        if ($bankAccountId !== null) {
            return $bankAccountId;
        }

        $cashAccount = BankAccount::firstOrCreate(
            [
                'account_number' => 'CASH-001',
            ],
            [
                'account_name' => 'Kas Tunai',
                'bank_name' => 'Kas Tunai',
                'saldo' => 0,
            ],
        );

        return (int) $cashAccount->id;
    }

    private function applyBankAccountSaldo(?int $bankAccountId, string $transactionType, float $totalAmount): void
    {
        if ($bankAccountId === null) {
            return;
        }

        $bankAccount = BankAccount::query()->lockForUpdate()->find($bankAccountId);

        if ($bankAccount === null) {
            throw ValidationException::withMessages([
                'bank_account_id' => 'Rekening bank tidak ditemukan.',
            ]);
        }

        $delta = $transactionType === 'penjualan' ? $totalAmount : -$totalAmount;
        $newSaldo = (float) $bankAccount->saldo + $delta;

        if ($newSaldo < 0) {
            throw ValidationException::withMessages([
                'bank_account_id' => 'Saldo rekening tidak mencukupi untuk transaksi ini.',
            ]);
        }

        $bankAccount->update(['saldo' => $newSaldo]);
    }

    private function revertBankAccountSaldo(?int $bankAccountId, string $transactionType, float $totalAmount): void
    {
        if ($bankAccountId === null) {
            return;
        }

        $bankAccount = BankAccount::query()->lockForUpdate()->find($bankAccountId);

        if ($bankAccount === null) {
            return;
        }

        $delta = $transactionType === 'penjualan' ? -$totalAmount : $totalAmount;
        $bankAccount->update([
            'saldo' => (float) $bankAccount->saldo + $delta,
        ]);
    }

    private function syncFinanceFromTransaction(
        int $transactionId,
        string $transactionType,
        float $totalAmount,
        int $bankAccountId,
        string $referenceNumber,
    ): void {
        $financeType = $transactionType === 'penjualan' ? 'income' : 'expense';

        Finance::updateOrCreate(
            ['transaction_id' => $transactionId],
            [
                'type' => $financeType,
                'amount' => $totalAmount,
                'description' => 'Aliran dana dari transaksi '.$referenceNumber,
                'bank_account_id' => $bankAccountId,
            ],
        );
    }
}
