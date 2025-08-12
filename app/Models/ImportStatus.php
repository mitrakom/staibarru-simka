<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'error_summary', 'description', 'result'];

    public static function isNotDone($name)
    {
        return self::where('name', $name)->where('status', '!=', 'done')->exists();
    }

    public static function isDone($name)
    {
        return self::where('name', $name)->where('status', '==', 'done')->exists();
    }

    public static function updateStatus($name, $status, $errorSummary = null, $result = null)
    {
        self::updateOrCreate(
            ['name' => $name],
            [
                'status' => $status,
                'error_summary' => $errorSummary,
                'result' => $result,
            ]
        );
    }
}
