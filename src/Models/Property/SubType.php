<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class SubType extends Model
{
    use UsesUuid, SetSlug;

    protected $guarded = [];

    // Relationships
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    protected function generateSlug()
    {
        return Str::slug($this->name);
    }
}
