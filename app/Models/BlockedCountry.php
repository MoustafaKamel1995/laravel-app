<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'country_name',
        'is_blocked',
        'block_reason'
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
    ];

    public static function isCountryBlocked($countryCode)
    {
        return static::where('country_code', $countryCode)
            ->where('is_blocked', true)
            ->exists();
    }
}
