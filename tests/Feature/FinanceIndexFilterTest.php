<?php

use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\Finance;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

test('finance index can be filtered by type bank date customer supplier and search', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $mainAccount = BankAccount::create([
        'account_name' => 'Kas Utama',
        'account_number' => '1110001',
        'bank_name' => 'Kas Tunai',
    ]);

    $secondaryAccount = BankAccount::create([
        'account_name' => 'Kas Cadangan',
        'account_number' => '2220002',
        'bank_name' => 'Bank BCA',
    ]);

    $matchingCustomer = Customer::create([
        'name' => 'PT Pelanggan Cocok',
        'phone' => '081111111111',
        'email' => 'customer@example.test',
        'address' => 'Alamat Customer',
    ]);

    $otherCustomer = Customer::create([
        'name' => 'PT Pelanggan Lain',
        'phone' => '082222222222',
        'email' => 'customer-other@example.test',
        'address' => 'Alamat Lain',
    ]);

    $matchingSupplier = Supplier::create([
        'name' => 'CV Supplier Cocok',
        'phone' => '083333333333',
        'email' => 'supplier@example.test',
        'address' => 'Alamat Supplier',
    ]);

    $otherSupplier = Supplier::create([
        'name' => 'CV Supplier Lain',
        'phone' => '084444444444',
        'email' => 'supplier-other@example.test',
        'address' => 'Alamat Supplier Lain',
    ]);

    $matchingTransaction = Transaction::create([
        'type' => 'penjualan',
        'customer_id' => $matchingCustomer->id,
        'supplier_id' => $matchingSupplier->id,
        'bank_account_id' => $mainAccount->id,
        'subtotal' => 1500000,
        'tax' => 0,
        'other_fees' => 0,
        'discount' => 0,
        'downpayment' => 0,
        'total_price' => 1500000,
        'sisa' => 0,
        'payment_method' => 'cash',
        'reference_number' => 'TRX-MATCH-001',
    ]);

    $differentPartyTransaction = Transaction::create([
        'type' => 'penjualan',
        'customer_id' => $otherCustomer->id,
        'supplier_id' => $otherSupplier->id,
        'bank_account_id' => $mainAccount->id,
        'subtotal' => 1250000,
        'tax' => 0,
        'other_fees' => 0,
        'discount' => 0,
        'downpayment' => 0,
        'total_price' => 1250000,
        'sisa' => 0,
        'payment_method' => 'cash',
        'reference_number' => 'TRX-OTHER-001',
    ]);

    $match = Finance::create([
        'type' => 'income',
        'amount' => 1500000,
        'description' => 'Penjualan Qurban A',
        'bank_account_id' => $mainAccount->id,
        'transaction_id' => $matchingTransaction->id,
    ]);
    $match->forceFill(['created_at' => Carbon::parse('2026-04-10')])->save();

    $differentBank = Finance::create([
        'type' => 'income',
        'amount' => 2400000,
        'description' => 'Penjualan Qurban B',
        'bank_account_id' => $secondaryAccount->id,
    ]);
    $differentBank->forceFill(['created_at' => Carbon::parse('2026-04-10')])->save();

    $differentType = Finance::create([
        'type' => 'expense',
        'amount' => 800000,
        'description' => 'Biaya Pakan A',
        'bank_account_id' => $mainAccount->id,
        'transaction_id' => $differentPartyTransaction->id,
    ]);
    $differentType->forceFill(['created_at' => Carbon::parse('2026-04-10')])->save();

    $differentParty = Finance::create([
        'type' => 'income',
        'amount' => 1250000,
        'description' => 'Penjualan Qurban C',
        'bank_account_id' => $mainAccount->id,
        'transaction_id' => $differentPartyTransaction->id,
    ]);
    $differentParty->forceFill(['created_at' => Carbon::parse('2026-04-10')])->save();

    $outOfRange = Finance::create([
        'type' => 'income',
        'amount' => 3000000,
        'description' => 'Penjualan Lama',
        'bank_account_id' => $mainAccount->id,
    ]);
    $outOfRange->forceFill(['created_at' => Carbon::parse('2026-01-05')])->save();

    $response = $this->get(route('finances.index', [
        'type' => 'income',
        'bank_account_id' => $mainAccount->id,
        'date_range' => '2026-04-01 to 2026-04-30',
        'customer' => $matchingCustomer->name,
        'supplier' => $matchingSupplier->name,
        'search' => 'Qurban',
    ]));

    $response->assertOk();
    $response->assertSeeText('Penjualan Qurban A');
    $response->assertDontSeeText('Penjualan Qurban B');
    $response->assertDontSeeText('Penjualan Qurban C');
    $response->assertDontSeeText('Biaya Pakan A');
    $response->assertDontSeeText('Penjualan Lama');
});
