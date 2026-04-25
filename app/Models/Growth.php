<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Growth extends Model
{
    protected $fillable = [
        'sheep_id',
        'weight',       // Selisih berat (actual_weight - previous_weight)
        'previous_weight',
        'actual_weight',
        'target',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'weight'          => 'decimal:2',
            'previous_weight' => 'decimal:2',
            'actual_weight'   => 'decimal:2',
            'target'          => 'decimal:2',
            'date'            => 'date',
        ];
    }

    public function sheep(): BelongsTo
    {
        return $this->belongsTo(Sheep::class);
    }
}
