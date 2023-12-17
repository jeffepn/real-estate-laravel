<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\AbstractAddress;
use Jeffpereira\RealEstate\Models\Traits\Property\Relationships;
use Jeffpereira\RealEstate\Models\Traits\Property\Scopes;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use JPAddress\Models\Address\Address;

class Property extends Model
{
    use UsesUuid;
    use SetSlug;
    use AbstractAddress;
    use Relationships;
    use Scopes;

    protected $guarded = [];

    public function getIsActiveAttribute()
    {
        return (bool) $this->active;
    }

    public function getIsInactiveAttribute()
    {
        return !(bool) $this->isActive;
    }

    public function generateAltImage()
    {
        return sprintf(
            '%s em %s %s %s',
            Str::title($this->sub_type->name),
            Str::title($this->address->neighborhood->name),
            Str::title($this->address->neighborhood->city->name),
            Str::title($this->address->neighborhood->city->state->initials)
        );
    }

    protected function generateSlug()
    {
        if ($this->slug) {
            return $this->slug;
        }
        $slug = $this->slugBasedInContext();
        $check = self::where('slug', $slug)->first();
        while ($check) {
            $slug = $this->slugBasedInContext() . '-' . ($this->code ? $this->code : (static::max('code') + 1));
            $check = self::where('slug', $slug)->first();
        }

        return $slug;
    }

    private function slugBasedInContext(): string
    {
        $subType = $this->sub_type ? $this->sub_type : SubType::find($this->sub_type_id);

        $generate = sprintf(
            '%s em %s - %s %s %s %s %s %s',
            Str::title($subType->name),
            Str::title($this->address->neighborhood->name),
            Str::title($this->address->neighborhood->city->state->initials),
            $this->max_dormitory ? $this->max_dormitory . ' dormitórios,' : '',
            $this->max_bathroom ? $this->max_bathroom . ' banheiros,' : '',
            $this->max_suite ? $this->max_suite . ' suites,' : '',
            $this->max_garage ? $this->max_garage . ' garagens,' : '',
            $this->max_restroom ? $this->max_restroom . ' lavabos,' : ''
        );

        return Str::slug(Str::limit($generate, 150));
    }

    private function getInstanceAddress(): Address
    {
        return $this->address;
    }
}
