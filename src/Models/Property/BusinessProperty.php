<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class BusinessProperty extends Pivot
{
    use UsesUuid;

    protected $table = 'business_properties';

    protected $guarded = [];

    // Relationships

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
