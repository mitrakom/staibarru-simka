<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dosen extends Model
{

    // protected $fillable = ['prodi_id', 'nidn', 'nuptk', 'nama', 'handphone', 'email', 'alamat', 'gelar_depan', 'gelar_belakang', 'status', 'jenis_kelamin', 'tanggal_lahir', 'tempat_lahir', 'foto', 'agama_id'];

    protected $guarded = [];

    public function prodi(): HasOne
    {
        return $this->hasOne(Prodi::class);
    }

    public function user()
    {
        return $this->morphOne(User::class, 'identity');
    }
}
