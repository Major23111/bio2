<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Authorization\User;

class DeliverySetting extends Model
{
    protected $table = 'delivery_settings';

    protected $fillable = [
        'key',
        'value',
        'label',
        'description',
        'updated_by_user_id',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'updated_by_user_id' => 'integer',
    ];

    /**
     * Get the user who last updated this setting.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    /**
     * Retrieve all delivery settings as a key => value map.
     */
    public static function getAllRates(): array
    {
        return static::pluck('value', 'key')->toArray();
    }

    /**
     * Bulk-save delivery rates from an associative array.
     */
    public static function saveRates(array $rates, ?int $userId = null): void
    {
        foreach ($rates as $key => $value) {
            static::where('key', $key)->update([
                'value' => $value,
                'updated_by_user_id' => $userId,
                'updated_at' => now(),
            ]);
        }
    }
}
