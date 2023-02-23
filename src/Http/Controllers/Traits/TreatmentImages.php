<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\Image as InterventionImage;
use Jeffpereira\RealEstate\Enum\AppSettingsEnum;
use Jeffpereira\RealEstate\Models\AppSettings;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;

trait TreatmentImages
{
    private function storageImage(string $entity, UploadedFile $image, string $altImage, ?bool $useWatterMark = false): array
    {
        $optmize = ConfigHelper::get("filesystem.entities.{$entity}.optmize");
        $path = ConfigHelper::get("filesystem.entities.{$entity}.path");
        $disk = ConfigHelper::get('filesystem.disk');
        $img = Image::make($image);

        if ($useWatterMark) {
            $img = $this->insertWatterMark($entity, $img);
        }
        $img->orientate();
        $imgThumbnail = clone $img;
        if ($optmize) {
            $img->resize(1080, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        $extension = 'jpg';
        $img->encode($extension, $optmize ? 60 : 100);

        $nameImage = Str::slug($altImage) . '-' . Str::uuid();
        $completNameImage = "{$path}/{$nameImage}.{$extension}";

        Storage::disk($disk)->put($completNameImage, $img);
        $wayThumbnail = $this->createThumbnail($imgThumbnail, 300, $entity, "{$nameImage}-small.{$extension}", $optmize);

        return [
            'way' => $completNameImage,
            'thumbnail' => $wayThumbnail,
        ];
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
            Storage::disk(ConfigHelper::get('filesystem.disk'))
                ->get($appSetting->value['image'])
        );
        $img->resize(null, $height * 0.5, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $img;
    }

    private function createThumbnail(InterventionImage $image, int $width, string $entity, string $nameImage, bool $optmize): string
    {
        $image->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $path = ConfigHelper::get("filesystem.entities.{$entity}.path");
        $disk = ConfigHelper::get('filesystem.disk');

        $nameImage = "{$path}/thumbnail/{$nameImage}";
        $image->encode('jpg', $optmize ? 80 : 100);
        Storage::disk($disk)->put($nameImage, $image);

        return $nameImage;
    }
}
