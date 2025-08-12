<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Krs extends Model
{

    // protected table = 'krs';
    // set model ini dengan nama tabel krss
    protected $table = 'krss';

    protected $fillable = ['mahasiswa_id', 'matakuliah_id', 'semester', 'kelas_id', 'verifikasi'];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function matakuliah(): BelongsTo
    {
        return $this->belongsTo(Matakuliah::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Get the nilai associated with the Krs
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nilai(): HasOne
    {
        return $this->hasOne(Nilai::class, 'krs_id');
        // return $this->hasOne(Nilai::class, 'krs_id', 'id');
        // return $this->hasOne(Nilai::class, 'id', 'krs_id');
    }
}
