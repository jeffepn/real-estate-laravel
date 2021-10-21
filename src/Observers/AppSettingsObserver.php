<?php

namespace Jeffpereira\RealEstate\Observers;

use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\AppSettings;
use Jeffpereira\RealEstate\Utilities\Terminologies;
use Illuminate\Support\Str;

class AppSettingsObserver
{
    const PREFIX_METHOD_DELETE = "destroySetting";

    public function deleting(AppSettings $appSettings)
    {
        $nameMethod = self::PREFIX_METHOD_DELETE . Str::studly($appSettings->name);
        if (method_exists($this, $nameMethod)) {
            $this->$nameMethod($appSettings);
        }
    }

    private function destroySettingWattermarkImageProperty(AppSettings $appSettings): bool
    {
        return Storage::disk(config('realestatelaravel.filesystem.disk'))
            ->delete($appSettings->value['image']);
    }
}
