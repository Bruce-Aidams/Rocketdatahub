<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value with caching.
     * Use a relatively short TTL for now (5 mins) so changes are picked up eventually
     * but middleware remains fast.
     */
    public static function getCached(string $key, $default = null)
    {
        return \Illuminate\Support\Facades\Cache::remember("setting_{$key}", 300, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    /**
     * Get multiple settings at once with caching.
     */
    public static function getManyCached(array $keys)
    {
        $results = [];
        foreach ($keys as $key) {
            $results[$key] = static::getCached($key);
        }
        return collect($results);
    }

    /**
     * Clear specific setting cache when updated
     */
    protected static function booted()
    {
        static::saved(function ($setting) {
            \Illuminate\Support\Facades\Cache::forget("setting_{$setting->key}");
        });

        static::deleted(function ($setting) {
            \Illuminate\Support\Facades\Cache::forget("setting_{$setting->key}");
        });
    }
}
