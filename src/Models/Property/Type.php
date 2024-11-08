<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\ScopeSearchCommon;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;

class Type extends Model
{
    use UsesUuid;
    use SetSlug;
    use ScopeSearchCommon;

    protected $guarded = [];

    // Relationships
    public function sub_types(): HasMany
    {
        return $this->hasMany(SubType::class);
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
