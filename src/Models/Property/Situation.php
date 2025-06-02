<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Database\Factories\SituationFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jeffpereira\RealEstate\Models\Traits\ScopeSearchCommon;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\UpperAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Situation extends Model
{
    use UsesUuid;
    use HasFactory;
    use SetSlug;
    use UpperAttributes;
    use ScopeSearchCommon;

    protected $guarded = [];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    protected function generateSlug(): string
    {
        return Str::slug($this->name);
    }

    protected function attributesUpper(): array
    {
        return ['name'];
    }

    protected static function newFactory()
    {
        return SituationFactory::new();
    }
}
