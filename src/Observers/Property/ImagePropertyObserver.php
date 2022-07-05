<?php

namespace Jeffpereira\RealEstate\Observers\Property;

use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;

class ImagePropertyObserver
{
    public const COLUMN_ORDER_IMAGE = 'order';
    public const COLUMN_ID_PROPERTY = 'property_id';

    public function deleting(ImageProperty $imageProperty)
    {
        Storage::disk(ConfigHelper::get('filesystem.disk'))
            ->delete($imageProperty->way);
    }

    public function creating(ImageProperty $imageProperty)
    {
        $imageProperty->order = ImageProperty::where(self::COLUMN_ID_PROPERTY, $imageProperty->property_id)
            ->max(self::COLUMN_ORDER_IMAGE) + 1;
    }
}
