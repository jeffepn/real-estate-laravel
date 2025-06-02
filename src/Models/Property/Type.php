<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Database\Factories\TypeFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\ScopeSearchCommon;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends Model
{
    use UsesUuid;
    use HasFactory;
    use SetSlug;
    use ScopeSearchCommon;

    protected $guarded = [];

    // Relationships
    public function sub_types(): HasMany
    {
        return $this->hasMany(SubType::class);
    }

    protected function generateSlug(): string
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

    protected static function newFactory()
    {
        return TypeFactory::new();
    }
}
