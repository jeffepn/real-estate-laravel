<?php

namespace Jeffpereira\RealEstate\Observers\Property;

use Illuminate\Support\Facades\Storage;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;

class ImagePropertyObserver
{
    const CONFIG_DISK = "realestatelaravel.filesystem.entities.properties.disk";
    const COLUMN_ORDER_IMAGE = "order";
    const COLUMN_ID_PROPERTY = "property_id";

    public function deleting(ImageProperty $imageProperty)
    {
        Storage::disk(config(self::CONFIG_DISK))
            ->delete($imageProperty->way);
    }

    public function creating(ImageProperty $imageProperty)
    {
        $imageProperty->order = ImageProperty::where(self::COLUMN_ID_PROPERTY, $imageProperty->property_id)
            ->max(self::COLUMN_ORDER_IMAGE) + 1;
    }
}
