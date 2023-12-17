<?php

namespace Jeffpereira\RealEstate\Enum;

class BaseEnum
{
    public static function all(): array
    {
        return (new \ReflectionClass(static::class))->getConstants();
    }
}
