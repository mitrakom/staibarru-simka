<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerguruanTinggi extends Model
{
    // protected $fillable = ['nama', 'akreditasi', 'alamat', 'telepon', 'email', 'website'];

    protected $guarded = [];

    public function prodis(): HasMany
    {
        return $this->hasMany(Prodi::class);
    }

    public function fakultas(): HasMany
    {
        return $this->hasMany(Fakultas::class);
    }
}
