<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class Business extends Model
{
    use UsesUuid, SetSlug;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($business) {
            $business->name = Str::upper($business->name);
        });
    }
    // Relationships
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'business_properties')
            ->using(BusinessProperty::class);
    }

    public function businessProperties(): HasMany
    {
        return $this->hasMany(BusinessProperty::class);
    }

    // Scopes
    public function scopeHasProperties($query)
    {
        return $query->whereHas('properties', function ($subQuery) {
            $subQuery->where('active', true);
        });
    }

    protected function generateSlug()
    {
        return Str::slug($this->name);
    }
}
