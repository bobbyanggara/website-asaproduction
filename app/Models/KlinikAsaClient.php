<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlinikAsaClient extends Model
{
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index')->orderBy('id');
    }
}
