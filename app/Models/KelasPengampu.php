<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelasPengampu extends Model
{
    protected $fillable = ['kelas_id', 'dosen_id'];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
