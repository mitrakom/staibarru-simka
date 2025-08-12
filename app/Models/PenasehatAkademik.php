<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenasehatAkademik extends Model
{
    protected $fillable = [
        'dosen_id',
        'mahasiswa_id',
        'semester',
    ];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
