<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Prodi extends Model
{

    // protected $fillable = [
    //     'fakultas_id',
    //     'perguruan_tinggi_id',
    //     'jenjang_id',
    //     'nama',
    //     'telepon',
    //     'kaprodi_nama',
    //     'kaprodi_nidn',
    // ];

    protected $guarded = [];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'identity');
    }

    /**
     * Get the jenjang that owns the Prodi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenjang(): BelongsTo
    {
        return $this->belongsTo(RefJenjang::class);
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function kurikulums(): HasMany
    {
        return $this->hasMany(Kurikulum::class);
    }

    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function perguruanTinggi(): BelongsTo
    {
        return $this->belongsTo(PerguruanTinggi::class);
    }

    public function kurikulum(): HasMany
    {
        return $this->hasMany(Kurikulum::class, 'prodi_id');
    }
}
