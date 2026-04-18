<?php

use App\Models\Customer;
use App\Models\Sheep;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('authenticated user can filter transactions by type', function () {
    actingAs(User::factory()->create());

    $sheep = Sheep::create([
        'type' => 'Garut',
        'price' => 4500000,
        'weight' => 35,
        'condition' => 'Sehat',
        'code' => 'DMB-001',
    ]);

    $customer = Customer::create([
        'name' => 'Customer A',
        'phone' => '081111111111',
    ]);

    $supplier = Supplier::create([
        'name' => 'Supplier B',
        'phone' => '082222222222',
    ]);

    Transaction::create([
        'type' => 'penjualan',
        'customer_id' => $customer->id,
        'sheep_id' => $sheep->id,
        'quantity' => 1,
        'total_price' => 5000000,
        'payment_method' => 'Tunai',
        'reference_number' => 'TRX-JUAL-001',
    ]);

    Transaction::create([
        'type' => 'pembelian',
        'supplier_id' => $supplier->id,
        'sheep_id' => $sheep->id,
        'quantity' => 1,
        'total_price' => 4200000,
        'payment_method' => 'Transfer Bank',
        'reference_number' => 'TRX-BELI-001',
    ]);

    $response = get(route('transactions.index', ['type' => 'penjualan']));

    $response->assertSuccessful();
    $response->assertSee('TRX-JUAL-001');
    $response->assertDontSee('TRX-BELI-001');
});

test('supplier is required when transaction type is pembelian', function () {
    actingAs(User::factory()->create());

    $sheep = Sheep::create([
        'type' => 'Garut',
        'price' => 4500000,
        'weight' => 35,
        'condition' => 'Sehat',
        'code' => 'DMB-002',
    ]);

    $response = post(route('transactions.store'), [
        'type' => 'pembelian',
        'sheep_id' => $sheep->id,
        'quantity' => 1,
        'total_price' => 4000000,
        'payment_method' => 'Tunai',
        'reference_number' => 'TRX-BELI-002',
    ]);

    $response->assertSessionHasErrors(['supplier_id']);
});
