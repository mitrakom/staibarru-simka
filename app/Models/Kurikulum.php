<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kurikulum extends Model
{

    // protected $fillable = ['prodi_id', 'nama', 'keterangan', 'jumlah_sks_lulus', 'jumlah_sks_wajib', 'jumlah_sks_pilihan', 'jumlah_sks_pkl', 'jumlah_sks_skripsi'];
    protected $guarded = [];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function matakuliahs(): HasMany
    {
        return $this->hasMany(Matakuliah::class);
    }
}
