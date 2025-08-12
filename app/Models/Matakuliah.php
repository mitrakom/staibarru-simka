<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matakuliah extends Model
{
    protected $guarded = [];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function krs()
    {
        return $this->hasMany(Krs::class);
    }

    protected $casts = [
        'ada_sap' => 'boolean',
        'ada_silabus' => 'boolean',
        'ada_bahan_ajar' => 'boolean',
        'ada_acara_praktek' => 'boolean',
        'ada_diktat' => 'boolean'
    ];
}
