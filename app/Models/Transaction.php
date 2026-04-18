<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'customer_id',
        'supplier_id',
        'bank_account_id',
        'subtotal',
        'tax',
        'other_fees',
        'discount',
        'downpayment',
        'total_price',
        'sisa',
        'payment_method',
        'payment_proof',
        'reference_number',
        'transaction_date',
        'due_date',
        'notes',
        'warehouse',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'float',
            'tax' => 'float',
            'other_fees' => 'float',
            'discount' => 'float',
            'downpayment' => 'float',
            'total_price' => 'float',
            'sisa' => 'float',
            'transaction_date' => 'date',
            'due_date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }
}
