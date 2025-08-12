<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fakultas extends Model
{
    // protected $fillable = ['perguruan_tinggi_id', 'nama', 'dekan', 'telepon', 'akreditasi'];

    protected $guarded = [];

    public function perguruanTinggi(): BelongsTo
    {
        return $this->belongsTo(PerguruanTinggi::class);
    }

    public function prodis(): HasMany
    {
        return $this->hasMany(Prodi::class);
    }
}
