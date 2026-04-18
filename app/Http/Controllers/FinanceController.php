<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Finance;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function index(): View
    {
        $finances = Finance::with(['bankAccount', 'transaction'])->latest()->paginate(15);
        $income = Finance::where('type', 'income')->sum('amount');
        $expense = Finance::where('type', 'expense')->sum('amount');

        return view('finances.index', [
            'finances' => $finances,
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
        ]);
    }

    public function create(): View
    {
        return $this->formView(
            finance: new Finance(),
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