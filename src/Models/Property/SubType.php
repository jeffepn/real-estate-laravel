<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\ScopeSearchCommon;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;

class SubType extends Model
{
    use UsesUuid;
    use SetSlug;
    use ScopeSearchCommon;

    protected $guarded = [];

    // Relationships
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
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

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($type) {
            $type->name = Str::upper($type->name);
        });
    }
}
