<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    protected $fillable = [
        'krs_id',
        'nilai',
        'huruf',
        'index',
        'bobot',
        'mutu',
        'is_transfer',
    ];

    public function krs(): BelongsTo
    {
        return $this->belongsTo(Krs::class);
    }
}
