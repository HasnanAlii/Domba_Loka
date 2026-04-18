<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Finance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CashMigrationController extends Controller
{
    public function store(Request $request, BankAccount $bankAccount): RedirectResponse
    {
        $validated = $request->validate([
            'from_bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $bankAccount) {
            $sourceAccount = BankAccount::query()
                ->lockForUpdate()
                ->find($validated['from_bank_account_id']);

            if ($sourceAccount === null) {
                throw ValidationException::withMessages([
                    'from_bank_account_id' => 'Rekening asal tidak ditemukan.',
                ]);
            }

            $targetAccount = BankAccount::query()->lockForUpdate()->find($bankAccount->id);
            if ($targetAccount === null) {
                throw ValidationException::withMessages([
                    'amount' => 'Rekening tujuan tidak ditemukan.',
                ]);
            }

            if ($sourceAccount->id === $targetAccount->id) {
                throw ValidationException::withMessages([
                    'from_bank_account_id' => 'Rekening asal dan tujuan tidak boleh sama.',
                ]);
            }

            $amount = (float) $validated['amount'];
            $sourceSaldo = (float) $sourceAccount->saldo;

            if ($sourceSaldo < $amount) {
                throw ValidationException::withMessages([
                    'amount' => 'Saldo rekening asal tidak mencukupi untuk migrasi dana.',
                ]);
            }

            $sourceAccount->update([
                'saldo' => $sourceSaldo - $amount,
            ]);

            $targetAccount->update([
                'saldo' => (float) $targetAccount->saldo + $amount,
            ]);

            $description = trim((string) ($validated['description'] ?? ''));
            $outDescription = $description !== ''
                ? $description
                : 'Migrasi dana ke rekening ' . $targetAccount->bank_name;
            $inDescription = $description !== ''
                ? $description
                : 'Migrasi dana dari rekening ' . $sourceAccount->bank_name;

            Finance::create([
                'type' => 'expense',
                'amount' => $amount,
                'description' => $outDescription,
                'bank_account_id' => $sourceAccount->id,
            ]);

            Finance::create([
                'type' => 'income',
                'amount' => $amount,
                'description' => $inDescription,
                'bank_account_id' => $targetAccount->id,
            ]);
        });

        return redirect()
            ->route('bank-accounts.show', $bankAccount)
            ->with('success', 'Migrasi dana berhasil.');
    }
}
