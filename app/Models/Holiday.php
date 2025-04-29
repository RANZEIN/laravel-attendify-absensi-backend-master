<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'type', // 'national', 'company', 'weekend'
        'description',
        'is_recurring', // For annual holidays like national holidays
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];

    /**
     * Get the user who created the holiday.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include holidays for a specific date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include holidays for a specific date.
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Check if a specific date is a holiday.
     */
    public static function isHoliday($date)
    {
        $date = Carbon::parse($date)->format('Y-m-d');
        return self::whereDate('date', $date)->exists();
    }

    /**
     * Get all holidays for a specific year.
     */
    public static function getYearlyHolidays($year)
    {
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();

        return self::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
    }

    /**
     * Get all holidays for a specific month.
     */
    public static function getMonthlyHolidays($year, $month)
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

        return self::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
    }
}
