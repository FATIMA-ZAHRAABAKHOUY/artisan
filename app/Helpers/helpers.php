<?php

use App\Helpers\PriceHelper;

if (! function_exists('mad_format')) {
    function mad_format(float|int|string $amount): string
    {
        return PriceHelper::format($amount);
    }
}
