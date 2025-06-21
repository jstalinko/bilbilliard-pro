<?php

namespace App\Helper;

class Helper
{

    public static function calculatePoint($amount, $roundDown = true)
    {
        $rate = 10000;
        if ($rate <= 0) {
            throw new \InvalidArgumentException("Rate harus lebih besar dari nol.");
        }

        $points = $amount / $rate;

        return $roundDown ? floor($points) : round($points);
    }
}
