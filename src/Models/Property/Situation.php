<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\UpperAttributes;

class Situation extends Model
{
    use UsesUuid;
    use SetSlug;
    use UpperAttributes;

    protected $guarded = [];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    protected function generateSlug()
    {
        return Str::slug($this->name);
    }

    protected function attributesUpper(): array
    {
        return ['name'];
    }
}
