<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PembayaranItem extends Model
{

    protected $fillable = [
        'angkatan',
        'keterangan',
        'jumlah',
    ];

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }
}
