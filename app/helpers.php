<?php

use App\Helpers\Currency;

if (! function_exists('format_money')) {
    function format_money(float|int|string|null $amount, string $currency = 'USD'): string
    {
        return Currency::format($amount, $currency);
    }
}

if (! function_exists('currency_symbol')) {
    function currency_symbol(string $currency): string
    {
        return Currency::symbol($currency);
    }
}

if (! function_exists('typography_styles')) {
    function typography_styles(?array $settings): string
    {
        if (! $settings) return '';
        $map = [
            'font_family' => 'font-family',
            'font_size' => 'font-size',
            'font_weight' => 'font-weight',
            'font_style' => 'font-style',
            'text_transform' => 'text-transform',
            'text_decoration' => 'text-decoration',
            'line_height' => 'line-height',
            'letter_spacing' => 'letter-spacing',
            'word_spacing' => 'word-spacing',
        ];
        $css = '';
        foreach ($map as $key => $prop) {
            $val = $settings[$key] ?? '';
            if ($val !== '' && $val !== null) {
                $unit = in_array($key, ['font_size']) ? 'px' : '';
                $css .= $prop . ':' . $val . $unit . ';';
            }
        }
        return $css;
    }
}

if (! function_exists('in_user_timezone')) {
    function in_user_timezone(\DateTimeInterface $date): \Carbon\Carbon
    {
        $tz = auth()->user()->timezone ?? 'UTC';
        return $date instanceof \Carbon\Carbon
            ? $date->copy()->setTimezone($tz)
            : \Carbon\Carbon::parse($date)->setTimezone($tz);
    }
}
