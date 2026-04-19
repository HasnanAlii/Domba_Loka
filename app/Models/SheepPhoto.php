<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SheepPhoto extends Model
{
    protected $fillable = ['sheep_id', 'path', 'sort_order'];

    public function sheep()
    {
        return $this->belongsTo(Sheep::class);
    }
}
