<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class UserSetting extends Model
{
    protected $table = 'user_settings';

    protected $fillable = ['user_id', 'key', 'value'];

    /* ─── Defaults ─── */

    public const DEFAULTS = [
        'theme.mode'         => 'light',        // light | dark | system
        'theme.color_preset' => 'biogenix-green', // biogenix-green | forest-green | modern-indigo | midnight-black
    ];

    /* ─── Static Helpers ─── */

    /**
     * Get a single setting value for a user, with fallback to default.
     */
    public static function getValue(int $userId, string $key, mixed $default = null): string
    {
        $fallback = $default ?? (self::DEFAULTS[$key] ?? '');

        return Cache::remember(
            "user_settings.{$userId}.{$key}",
            3600,
            fn () => static::where('user_id', $userId)->where('key', $key)->value('value') ?? $fallback
        );
    }

    /**
     * Set (upsert) a single setting for a user.
     */
    public static function setValue(int $userId, string $key, ?string $value): void
    {
        static::updateOrCreate(
            ['user_id' => $userId, 'key' => $key],
            ['value' => $value]
        );

        Cache::forget("user_settings.{$userId}.{$key}");
        Cache::forget("user_settings.{$userId}.all");
    }

    /**
     * Get all settings for a user as a flat key => value array.
     */
    public static function getAllForUser(int $userId): array
    {
        $stored = Cache::remember(
            "user_settings.{$userId}.all",
            3600,
            fn () => static::where('user_id', $userId)->pluck('value', 'key')->toArray()
        );

        return array_merge(self::DEFAULTS, $stored);
    }

    /**
     * Save multiple settings at once for a user.
     */
    public static function saveMany(int $userId, array $settings): void
    {
        foreach ($settings as $key => $value) {
            if (array_key_exists($key, self::DEFAULTS)) {
                static::setValue($userId, $key, $value);
            }
        }

        Cache::forget("user_settings.{$userId}.all");
    }

    /**
     * Reset all settings for a user to defaults.
     */
    public static function resetForUser(int $userId): void
    {
        static::where('user_id', $userId)->delete();

        foreach (array_keys(self::DEFAULTS) as $key) {
            Cache::forget("user_settings.{$userId}.{$key}");
        }
        Cache::forget("user_settings.{$userId}.all");
    }
}
