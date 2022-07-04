<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as InterventionImage;
use Jeffpereira\RealEstate\Enum\AppSettingsEnum;
use Jeffpereira\RealEstate\Models\AppSettings;

trait TreatmentImages
{

    private function storageImage(string $entity, UploadedFile $image, string $altImage, ?bool $useWatterMark = false): string
    {
        $optmize = config("realestatelaravel.filesystem.entities.{$entity}.optmize") ?? true;
        $path = config("realestatelaravel.filesystem.entities.{$entity}.path") ?? $entity;
        $disk = config("realestatelaravel.filesystem.entities.{$entity}.disk") ?? 'public';
        $img = Image::make($image);
        if ($useWatterMark) {
            $img = $this->insertWatterMark($entity, $img);
        }
        $img->orientate();
        if ($optmize) {
            $img->resize(null, 1080, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $img->encode('jpg', $optmize ? 60 : 100);

        $nameImage =  $path . '/' . Str::slug($altImage) .  Str::uuid() . '.jpg';
        Storage::disk($disk)->put($nameImage, $img);
        return $nameImage;
    }

    private function insertWatterMark(string $entity, InterventionImage $img): InterventionImage
    {

        $appSetting = AppSettings::whereName(AppSettingsEnum::translateEntity($entity))
            ->first();
        return $appSetting
            ? $img->insert(
                $this->formatImageInserWatterMark($appSetting, $img->height()),
                'center'
            )
            : $img;
    }

    private function formatImageInserWatterMark(AppSettings $appSetting, int $height): InterventionImage
    {
        $img = Image::make(
            Storage::disk(config('realestatelaravel.filesystem.disk'))
                ->get($appSetting->value['image'])
        );
        $img->resize(null, $height * 0.5, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $img;
    }
}
