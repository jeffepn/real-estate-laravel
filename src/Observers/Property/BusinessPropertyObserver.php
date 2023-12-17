<?php

namespace Jeffpereira\RealEstate\Observers\Property;

use Jeffpereira\RealEstate\Enum\BusinessPropertySituationEnum;
use Jeffpereira\RealEstate\Events\BusinessPropertyFinalizedEvent;
use Jeffpereira\RealEstate\Models\Property\BusinessProperty;

class BusinessPropertyObserver
{
    public function saved(BusinessProperty $businessProperty)
    {
        if ($businessProperty->getOriginal('status_situation') === BusinessPropertySituationEnum::IN_PROGRESS
            && $businessProperty->status_situation === BusinessPropertySituationEnum::COMPLETED
        ) {
            BusinessPropertyFinalizedEvent::dispatch($businessProperty->id);
        }
    }
}
