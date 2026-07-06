<?php

namespace App\Helpers;

class Currency
{
    public const SYMBOLS = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
    ];

    public const OPTIONS = ['USD', 'EUR', 'GBP'];

    public static function symbol(string $code): string
    {
        return self::SYMBOLS[$code] ?? $code;
    }

    public static function format(float|int|string|null $amount, string $code = 'USD'): string
    {
        return self::symbol($code) . number_format((float) $amount, 2);
    }

    public static function label(string $code): string
    {
        return match ($code) {
            'USD' => 'USD ($)',
            'EUR' => 'EUR (€)',
            'GBP' => 'GBP (£)',
            default => $code,
        };
    }
}
