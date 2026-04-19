<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sheep extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_id',
        'price',
        'weight',
        'condition',
        'code',
        'status',
        'photo',
        'age',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'weight' => 'float',
        ];
    }

    public function sheepType()
    {
        return $this->belongsTo(SheepType::class, 'type_id');
    }

    public function growths(): HasMany
    {
        return $this->hasMany(Growth::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_details', 'sheep_id', 'transaction_id')
                    ->withPivot(['quantity', 'price', 'discount', 'total_price'])
                    ->withTimestamps();
    }

    public function photos(): HasMany
    {
        return $this->hasMany(SheepPhoto::class)->orderBy('sort_order');
    }
}
