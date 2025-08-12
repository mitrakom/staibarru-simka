<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KurikulumMatakuliah extends Model
{
    protected $guarded = [];

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function matakuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
