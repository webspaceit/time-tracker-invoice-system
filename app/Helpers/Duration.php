<?php

namespace App\Helpers;

class Duration
{
    public static function toSeconds(?string $duration): int
    {
        if ($duration === null || trim($duration) === '') {
            return 0;
        }

        $parts = array_map('intval', explode(':', trim($duration)));

        return match (count($parts)) {
            3 => ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2],
            2 => ($parts[0] * 3600) + ($parts[1] * 60),
            1 => $parts[0],
            default => 0,
        };
    }

    public static function fromSeconds(int $seconds): string
    {
        if ($seconds <= 0) {
            return '00:00:00';
        }

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }

    public static function sum(array $durations): string
    {
        $total = 0;

        foreach ($durations as $duration) {
            $total += self::toSeconds(is_string($duration) ? $duration : null);
        }

        return self::fromSeconds($total);
    }
}
