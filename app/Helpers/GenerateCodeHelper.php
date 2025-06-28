<?php

namespace App\Helpers;

final class GenerateCodeHelper
{
    public static function generate(): string
    {
        return str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }
}
