<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Database\Factories\BusinessPropertyFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Jeffpereira\RealEstate\Enum\BusinessPropertySituationEnum;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessProperty extends Pivot
{
    use UsesUuid;
    use HasFactory;

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

    public function getIsCompletedAttribute(): bool
    {
        return $this->status_situation === BusinessPropertySituationEnum::COMPLETED;
    }

    public function getIsDiscountedAttribute(): bool
    {
        return $this->old_value && $this->value && $this->old_value > $this->value;
    }

    protected static function newFactory()
    {
        return BusinessPropertyFactory::new();
    }
}
