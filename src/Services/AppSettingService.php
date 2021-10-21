<?php

namespace Jeffpereira\RealEstate\Services;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Jeffpereira\RealEstate\Models\AppSettings;
use Illuminate\Support\Str;

class AppSettingService
{

    public function create(array $data): AppSettings
    {
        return AppSettings::create($data);
    }

    public function update(array $data): ?AppSettings
    {
        try {
            $appSettings = AppSettings::findOrFail(Arr::get($data, 'id'));
            $appSettings->update(Arr::except($data, ['id']));
            return $appSettings;
        } catch (ModelNotFoundException $th) {
            throw new Exception($th->getMessage());
            return null;
        }
    }

    public function registerWattermarkImageProperty(array $data): AppSettings
    {
        return AppSettings::create([
            'name' => $data['name'],
            'value' => Arr::get($data, 'value', [])
        ]);
    }
}
