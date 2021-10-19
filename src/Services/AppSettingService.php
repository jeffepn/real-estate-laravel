<?php

namespace Jeffpereira\RealEstate\Services;

use Exception;
use Illuminate\Support\Arr;
use Jeffpereira\RealEstate\Models\AppSettings;
use Illuminate\Support\Str;

class AppSettingService
{
    const PREFIX_METHOD_REGISTER = 'register';

    public function create(array $data): AppSettings
    {
        $nameMethod = self::PREFIX_METHOD_REGISTER . Str::studly(Arr::get($data, 'name'));
        if (method_exists($this, $nameMethod)) {
            return $this->$nameMethod($data);
        }
        throw new Exception("The methos not exists - $nameMethod");
    }

    public function registerWattermarkImageProperty(array $data): AppSettings
    {
        return AppSettings::create([
            'name' => $data['name'],
            'value' => Arr::get($data, 'value', [])
        ]);
    }
}
