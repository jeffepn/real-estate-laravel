<?php

namespace Jeffpereira\RealEstate\Utilities;

class Terminologies
{
    public static function get($string)
    {
        $json = json_decode(file_get_contents(__DIR__ . '/terminologies.json'));

        return $json->$string;
    }
}
