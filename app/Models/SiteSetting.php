<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Retrieve a setting value by key, with an optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::query()->where('key', $key)->value('value');

        return $value !== null ? $value : $default;
    }

    /**
     * Persist (insert or update) a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );
    }

    /**
     * Retrieve all settings whose key starts with $prefix,
     * returning them as an associative array keyed by the suffix
     * after the prefix. E.g. prefix "about_" turns "about_hero_title" → "hero_title".
     */
    public static function group(string $prefix): array
    {
        return static::query()
            ->where('key', 'like', $prefix . '%')
            ->pluck('value', 'key')
            ->mapWithKeys(fn ($value, $key) => [
                substr($key, strlen($prefix)) => $value,
            ])
            ->toArray();
    }
}
