<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlinikAsaPage extends Model
{
    protected $guarded = [];

    /**
     * Halaman ini kontennya 1 baris saja (mirip Setting). Selalu ambil
     * (atau buat) baris #1.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
