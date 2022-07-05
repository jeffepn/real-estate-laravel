<?php

namespace Jeffpereira\RealEstate\Utilities;

use Illuminate\Support\Arr;

class Terminologies
{
    public static function get($string)
    {
        $json = json_decode(file_get_contents(__DIR__ . '/terminologies.json'), true);

        return Arr::get($json, $string);
    }
}
