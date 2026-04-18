<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\Finance;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'type' => ['nullable', Rule::in(['income', 'expense'])],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'date_range' => ['nullable', 'string', 'max:50'],
            'customer' => ['nullable', 'string', 'max:255'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $filters = [
            'type' => $validated['type'] ?? null,
            'bank_account_id' => $validated['bank_account_id'] ?? null,
            'date_range' => trim((string) ($validated['date_range'] ?? '')),
            'customer' => trim((string) ($validated['customer'] ?? '')),
            'supplier' => trim((string) ($validated['supplier'] ?? '')),
            'search' => trim((string) ($validated['search'] ?? '')),
        ];

        $rangeStart = null;
        $rangeEnd = null;
        if ($filters['date_range'] !== '') {
            if (str_contains($filters['date_range'], ' to ')) {
                [$rangeStart, $rangeEnd] = explode(' to ', $filters['date_range']);
            } else {
                $rangeStart = $filters['date_range'];
            }
        }

        $financeQuery = Finance::query()
            ->with(['bankAccount', 'transaction'])
            ->when($filters['type'], function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($filters['bank_account_id'], function ($query, $bankAccountId) {
                $query->where('bank_account_id', $bankAccountId);
            })
            ->when($filters['customer'] !== '', function ($query) use ($filters) {
                $query->whereHas('transaction.customer', function ($customerQuery) use ($filters) {
                    $customerQuery->where('name', $filters['customer']);
                });
            })
            ->when($filters['supplier'] !== '', function ($query) use ($filters) {
                $query->whereHas('transaction.supplier', function ($supplierQuery) use ($filters) {
                    $supplierQuery->where('name', $filters['supplier']);
                });
            })
            ->when($rangeStart, function ($query) use ($rangeStart, $rangeEnd) {
                if ($rangeEnd) {
                    $query->whereBetween('created_at', [
                        $rangeStart.' 00:00:00',
                        $rangeEnd.' 23:59:59',
                    ]);

                    return;
                }

                $query->whereDate('created_at', $rangeStart);
            })
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $search = $filters['search'];

                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('description', 'like', "%{$search}%")
                        ->orWhereHas('transaction', function ($transactionQuery) use ($search) {
                            $transactionQuery->where('reference_number', 'like', "%{$search}%");
                        })
                        ->orWhereHas('transaction.customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('transaction.supplier', function ($supplierQuery) use ($search) {
                            $supplierQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('bankAccount', function ($bankQuery) use ($search) {
                            $bankQuery->where('bank_name', 'like', "%{$search}%")
                                ->orWhere('account_number', 'like', "%{$search}%");
                        });
                });
            })
            ->latest();

        $finances = (clone $financeQuery)->paginate(15)->withQueryString();
        $income = (clone $financeQuery)->where('type', 'income')->sum('amount');
        $expense = (clone $financeQuery)->where('type', 'expense')->sum('amount');

        return view('finances.index', [
            'finances' => $finances,
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'filters' => $filters,
            'bankAccounts' => $this->bankAccounts(),
            'customers' => Customer::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return $this->formView(
            finance: new Finance,
            pageTitle: 'Tambah Data Keuangan',
            submitLabel: 'Simpan Data',
            action: route('finances.store'),
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'transaction_id' => ['nullable', 'exists:transactions,id'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
        ]);

        Finance::create($validated);

        return redirect()->route('finances.index')->with('success', 'Keuangan berhasil ditambahkan.');
    }

    public function show(Finance $finance): View
    {
        $finance->load(['bankAccount', 'transaction']);

        return view('finances.show', compact('finance'));
    }

    public function edit(Finance $finance): View
    {
        return $this->formView(
            finance: $finance,
            pageTitle: 'Edit Data Keuangan',
            submitLabel: 'Perbarui Data',
            action: route('finances.update', $finance),
            method: 'PUT',
        );
    }

    public function update(Request $request, Finance $finance): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'transaction_id' => ['nullable', 'exists:transactions,id'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
        ]);

        $finance->update($validated);

        return redirect()->route('finances.index')->with('success', 'Data keuangan berhasil diperbarui.');
    }

    public function destroy(Finance $finance): RedirectResponse
    {
        $finance->delete();

        return redirect()->route('finances.index')->with('success', 'Data keuangan berhasil dihapus.');
    }

    private function formView(
        Finance $finance,
        string $pageTitle,
        string $submitLabel,
        string $action,
        string $method = 'POST',
    ): View {
        return view('finances.form', [
            'finance' => $finance,
            'pageTitle' => $pageTitle,
            'submitLabel' => $submitLabel,
            'action' => $action,
            'method' => $method,
            'bankAccounts' => $this->bankAccounts(),
            'transactions' => $this->transactions(),
        ]);
    }

    private function bankAccounts(): Collection
    {
        return BankAccount::orderBy('bank_name')->get();
    }

    private function transactions(): Collection
    {
        return Transaction::orderBy('reference_number')->get();
    }
}
