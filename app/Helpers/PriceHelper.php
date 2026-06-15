<?php

namespace App\Helpers;

class PriceHelper
{
    public static function format(float|int|string $amount): string
    {
        $formatted = number_format((float) $amount, 0, ',', ' ');

        return $formatted.' MAD';
    }
}
