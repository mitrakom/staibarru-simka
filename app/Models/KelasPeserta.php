<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelasPeserta extends Model
{
    protected $fillable = ['kelas_id', 'krs_id'];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function krs(): BelongsTo
    {
        return $this->belongsTo(Krs::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->krs->mahasiswa();
    }

    // public function getMahasiswaNimAttribute()
    // {
    //     return $this->krs->mahasiswa->nim ?? null;
    // }


    public function getMahasiswaNimAttribute()
    {
        return $this->krs?->mahasiswa?->nim ?? null;
    }

    public function getMahasiswaNamaAttribute()
    {
        return $this->krs?->mahasiswa?->nama ?? null;
    }
}
