<?php

namespace Jeffpereira\RealEstate\Observers\Property;

use Jeffpereira\RealEstate\Models\Property\Property;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class PropertyObserver
{
    const COLUMN_CODE = "code";

    public function deleting(Property $property)
    {
        $property->images->map(function ($image) {
            $image->delete();
        });

        $property->businessesProperty->map(function ($businesseProperty) {
            $businesseProperty->delete();
        });
    }

    public function creating(Property $property)
    {
        if (!$property->code) {
            $property->code = Property::max(self::COLUMN_CODE) + 1;
        }
    }

    public function updating(Property $property)
    {
        $this->checkIfPossibleUpdateActiveProperty($property);
    }

    private function checkIfPossibleUpdateActiveProperty(Property $property): bool
    {
        $haveBusinessesProperty = ($property->businessesProperty->count() > 0);
        $haveMedia = $property->images->count() || ($property->embed ? $property->embed : $property->getOriginal('embed'));
        if (!$property->active || ($haveBusinessesProperty && $haveMedia)) {
            return true;
        }
        logger("haveBusinessesProperty", [$haveBusinessesProperty]);
        logger("haveMedia", [$haveMedia]);
        logger("property->active", [$property->active]);
        throw new \Exception(Terminologies::get('all.property.not_publish_without_dependences') . "kkkk");
    }
}
