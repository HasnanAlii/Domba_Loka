<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SheepType extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public function sheep()
    {
        return $this->hasMany(Sheep::class, 'type_id');
    }
}
