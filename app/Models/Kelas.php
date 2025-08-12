<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    protected $fillable = ['nama', 'keterangan', 'semester', 'prodi_id', 'matakuliah_id', 'jenis_kelas'];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function matakuliah(): BelongsTo
    {
        return $this->belongsTo(Matakuliah::class);
    }

    public function pesertas(): HasMany
    {
        return $this->hasMany(KelasPeserta::class);
    }

    public function pengampus(): HasMany
    {
        return $this->hasMany(KelasPengampu::class);
    }
}
