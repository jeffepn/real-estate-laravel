<?php

namespace Jeffpereira\RealEstate\Models\Traits\Property;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jeffpereira\RealEstate\Models\Property\Business;
use Jeffpereira\RealEstate\Models\Property\BusinessProperty;
use Jeffpereira\RealEstate\Models\Property\ImageProperty;
use Jeffpereira\RealEstate\Models\Property\Situation;
use Jeffpereira\RealEstate\Models\Property\SubType;
use JPAddress\Models\Address\Address;

trait Relationships
{
    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_properties')
            ->using(BusinessProperty::class)
            ->withPivot([
                'value', 'old_value', 'id',
            ]);
    }

    public function businessesProperty(): HasMany
    {
        return $this->hasMany(BusinessProperty::class);
    }

    public function sub_type(): BelongsTo
    {
        return $this->belongsTo(SubType::class);
    }

    public function situation(): BelongsTo
    {
        return $this->belongsTo(Situation::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ImageProperty::class)->orderBy('order');
    }
}
