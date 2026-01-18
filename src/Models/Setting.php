<?php

namespace PacificDev\BlogAi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Setting extends Model
{
    use HasFactory;
    
    // Keep raw storage and only JSON-encode arrays/objects when needed.
    // Remove automatic array cast to avoid encoding scalar strings into JSON strings.
    protected $casts = [];

    protected $fillable = ['key', 'value'];

    /**
     * Retrieve the value of a setting based on the given key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        $raw = $setting->getAttributes()['value'] ?? null;

        if (is_null($raw) || $raw === '') {
            return $default;
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return $raw;
    }

    /**
     * Update or create a setting with the given key and value.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        // Convert null to empty string to satisfy NOT NULL constraint
        if (is_null($value)) {
            $stored = '';
        } elseif (is_array($value) || is_object($value)) {
            $stored = json_encode($value);
        } else {
            $stored = $value;
        }

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $stored]
        );
    }
    

}
