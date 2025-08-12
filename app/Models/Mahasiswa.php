<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    public function user()
    {
        return $this->morphOne(User::class, 'identity');
    }


    /**
     * Get the prodi that owns the Mahasiswa
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
    }
}
