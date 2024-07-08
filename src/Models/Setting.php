<?php

namespace PacificDev\BlogAi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Setting extends Model
{
    use HasFactory;
    
    protected $casts = [
        'value' => 'array',
    ];

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
        return $setting ? $setting->value : $default;
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
        //dd($key, $value);
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
    

}
