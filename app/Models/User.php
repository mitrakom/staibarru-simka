<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'identity_type',
        'identity_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    // protected function prodi(): BelongsTo
    // {
    //     return $this->belongsTo(Prodi::class);
    // }

    // public function dosen(): BelongsTo
    // {
    //     return $this->belongsTo(Dosen::class);
    // }

    public function identity(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeWhereNim($query, $nim)
    {
        return $query->whereHasMorph(
            'identity',
            Mahasiswa::class,
            function ($query) use ($nim) {
                $query->where('nim', $nim);
            }
        );
    }

    public function scopeWhereNidn($query, $nidn)
    {
        return $query->whereHasMorph(
            'identity',
            Dosen::class,
            function ($query) use ($nidn) {
                $query->where('nidn', $nidn);
            }
        );
    }

    public function scopeWhereNuptk($query, $nuptk)
    {
        return $query->whereHasMorph(
            'identity',
            Dosen::class,
            function ($query) use ($nuptk) {
                $query->where('nuptk', $nuptk);
            }
        );
    }

    public function isMahasiswa()
    {
        return $this->identity instanceof Mahasiswa;
    }

    public function isDosen()
    {
        return $this->identity instanceof Dosen;
    }

    /**
     * Get the prodi that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }
}
