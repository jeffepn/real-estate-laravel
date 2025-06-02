<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Database\Factories\BusinessFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use UsesUuid;
    use HasFactory;
    use SetSlug;

    protected $guarded = [];

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

    public function getHasSituationAttribute(): bool
    {
        return !empty($this->name_completed);
    }

    protected function generateSlug(): string
    {
        return Str::slug($this->name);
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($business) {
            $business->name = Str::upper($business->name);
        });
    }

    protected static function newFactory()
    {
        return BusinessFactory::new();
    }
}
