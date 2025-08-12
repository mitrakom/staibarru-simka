<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{

    protected $fillable = [
        'pembayaran_item_id',
        'mahasiswa_id',
        'keterangan',
        'jumlah',
        'tanggal_bayar',
        'bukti_bayar',
        'semester',
        'status',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function pembayaranItem(): BelongsTo
    {
        return $this->belongsTo(pembayaranItem::class);
    }
}
