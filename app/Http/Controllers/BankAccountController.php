<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankAccountController extends Controller
{
    public function index(): View
    {
        $bankAccounts = BankAccount::latest()->paginate(15);

        return view('bank-accounts.index', compact('bankAccounts'));
    }

    public function create(): View
    {
        return view('bank-accounts.form', [
            'bankAccount' => new BankAccount(),
            'pageTitle' => 'Tambah Rekening Bank',
            'submitLabel' => 'Simpan Data',
            'action' => route('bank-accounts.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:50'],
            'bank_name' => ['required', 'string', 'max:255'],
            'saldo' => ['required', 'numeric', 'min:0'],
        ]);

        $bankAccount = BankAccount::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Rekening bank berhasil ditambahkan.', 'redirect' => route('bank-accounts.index')]);
        }

        return redirect()->route('bank-accounts.index')->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    public function show(BankAccount $bankAccount): View
    {
        $bankAccount->load([
            'transactions' => function ($query) {
                $query->with(['customer:id,name', 'supplier:id,name'])
                    ->latest('transaction_date');
            },
            'finances' => function ($query) {
                $query->whereNull('transaction_id')->latest();
            },
        ]);

        $cashAccount = BankAccount::query()
            ->where('account_number', 'CASH-001')
            ->first();

        // Gabungkan data transaksi dan finansial (migrasi)
        $history = collect();

        foreach ($bankAccount->transactions as $trans) {
            $history->push((object)[
                'date' => $trans->transaction_date,
                'ref' => $trans->reference_number,
                'type' => $trans->type === 'penjualan' ? 'masuk' : 'keluar',
                'party' => $trans->type === 'penjualan' ? ($trans->customer?->name ?? 'Pelanggan') : ($trans->supplier?->name ?? 'Pemasok'),
                'amount' => $trans->total_price,
                'original_type' => 'transaction'
            ]);
        }

        foreach ($bankAccount->finances as $fine) {
            $history->push((object)[
                'date' => $fine->created_at,
                'ref' => 'FIN-' . str_pad($fine->id, 5, '0', STR_PAD_LEFT),
                'type' => $fine->type === 'income' ? 'masuk' : 'keluar',
                'party' => $fine->description,
                'amount' => $fine->amount,
                'original_type' => 'finance'
            ]);
        }

        $history = $history->sortByDesc('date');

        $allAccounts = BankAccount::where('id', '!=', $bankAccount->id)->get();

        return view('bank-accounts.show', compact('bankAccount', 'cashAccount', 'history', 'allAccounts'));
    }

    public function edit(BankAccount $bankAccount): View
    {
        return view('bank-accounts.form', [
            'bankAccount' => $bankAccount,
            'pageTitle' => 'Edit Rekening Bank',
            'submitLabel' => 'Perbarui Data',
            'action' => route('bank-accounts.update', $bankAccount),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, BankAccount $bankAccount): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:50'],
            'bank_name' => ['required', 'string', 'max:255'],
            'saldo' => ['required', 'numeric', 'min:0'],
        ]);

        $bankAccount->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Rekening bank berhasil diperbarui.', 'redirect' => route('bank-accounts.index')]);
        }

        return redirect()->route('bank-accounts.index')->with('success', 'Rekening bank berhasil diperbarui.');
    }

    public function destroy(BankAccount $bankAccount): RedirectResponse|JsonResponse
    {
        if ($bankAccount->transactions()->exists() || $bankAccount->finances()->exists()) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Rekening ini memiliki catatan transaksi atau arus kas.']);
            }
            return redirect()->route('bank-accounts.index')->with('error', 'Rekening ini memiliki catatan transaksi atau arus kas.');
        }

        $bankAccount->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Rekening bank berhasil dihapus.', 'redirect' => route('bank-accounts.index')]);
        }

        return redirect()->route('bank-accounts.index')->with('success', 'Rekening bank berhasil dihapus.');
    }
}
