<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemFeatureFlag extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'is_enabled',
        'description'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public static function isEnabled(string $key, bool $default = true): bool
    {
        $flag = static::where('key', $key)->first();
        return $flag ? (bool) $flag->is_enabled : $default;
    }

    public static function toggle(string $key): bool
    {
        $flag = static::where('key', $key)->first();
        if ($flag) {
            $flag->is_enabled = !$flag->is_enabled;
            $flag->save();
            return $flag->is_enabled;
        }
        return false;
    }
}

