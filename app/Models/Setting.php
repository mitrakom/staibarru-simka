<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    /**
     * Mengambil nilai setting berdasarkan key tertentu.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return self::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Menyimpan atau memperbarui nilai setting berdasarkan key.
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $type
     * @param string|null $description
     * @return bool
     */
    public static function set(string $key, $value, ?string $type = null, ?string $description = null): bool
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type ?? self::where('key', $key)->value('type') ?? 'string',
                'description' => $description ?? self::where('key', $key)->value('description'),
            ]
        ) ? true : false;
    }

    /**
     * Mengecek apakah KRS Online masih berlaku atau tidak.
     *
     * @return bool
     */
    public static function isKrsOnlineActive(): bool
    {
        $startDate = self::get('krs_online_start_date');
        $endDate = self::get('krs_online_end_date');

        if (!$startDate || !$endDate) {
            return false; // Jika tanggal tidak valid, KRS dianggap tidak aktif
        }

        $now = Carbon::now()->format('Y-m-d');

        return $now >= $startDate && $now <= $endDate;
    }
}
