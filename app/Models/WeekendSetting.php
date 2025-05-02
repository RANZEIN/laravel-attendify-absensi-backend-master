<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekendSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'weekend_days',
    ];

    /**
     * Get the weekend days as an array.
     */
    public function getWeekendDaysArrayAttribute()
    {
        return json_decode($this->weekend_days) ?: [0, 6]; // Default to Sunday and Saturday
    }

    /**
     * Check if a day is a weekend.
     */
    public static function isWeekend($dayOfWeek)
    {
        $settings = self::first();
        if (!$settings) {
            return in_array($dayOfWeek, [0, 6]); // Default to Sunday and Saturday
        }

        return in_array($dayOfWeek, json_decode($settings->weekend_days));
    }
}
