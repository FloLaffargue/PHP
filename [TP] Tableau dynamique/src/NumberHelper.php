<?php

namespace App;

class NumberHelper {

    public static function price(float $number, string $devise = "€"): string
    {
        return number_format($number, 0, '', ' ') . ' ' . $devise;

    }
}