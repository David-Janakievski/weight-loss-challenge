<?php

namespace App\Support;

use Carbon\Carbon;

class Challenge
{
    // Adjust these to your actual challenge dates.
    public static function startDate(): Carbon
    {
        return Carbon::parse(config('challenge.start_date'));
    }

    public static function endDate(): Carbon
    {
        return Carbon::parse(config('challenge.end_date'));
    }

    public static function isTuesday(?Carbon $date = null): bool
    {
        $date = $date ?: now();
        return $date->isTuesday();
    }

    /**
     * Week number is computed from the first Tuesday on/after the challenge start date.
     * Week 1 = first Tuesday, Week 2 = following Tuesday, etc.
     */
    public static function currentWeekNumber(?Carbon $date = null): int
    {
        $date = $date ?: now();
        $firstTuesday = self::startDate()->copy();
        while (!$firstTuesday->isTuesday()) {
            $firstTuesday->addDay();
        }

        if ($date->lt($firstTuesday)) {
            return 0;
        }

        return (int) floor($firstTuesday->diffInWeeks($date)) + 1;
    }

    public static function isEnded(): bool
    {
        return now()->gt(self::endDate()->endOfDay());
    }

    public static function daysRemaining(): int
    {
        $days = now()->startOfDay()->diffInDays(self::endDate()->endOfDay(), false);
        return max(0, (int) $days);
    }
}