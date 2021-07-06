<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Jeffpereira\RealEstate\Models\Traits\SetSlug;
use Jeffpereira\RealEstate\Models\Traits\UsesUuid;
use JPAddress\Models\Address\Address;

class Property extends Model
{
    use UsesUuid, SetSlug;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($property) {
            $property->code = static::max('code') + 1;
        });
        self::deleting(function ($property) {
            $property->images->map(function ($image) {
                $image->delete();
            });
            $property->businessesProperty->map(function ($businesseProperty) {
                $businesseProperty->delete();
            });
        });
    }

    // Relationships
    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany('Jeffpereira\RealEstate\Models\Property\Business', "business_properties")
            ->using('Jeffpereira\RealEstate\Models\Property\BusinessProperty')
            ->withPivot([
                'value', 'id'
            ]);
    }
    public function businessesProperty(): HasMany
    {
        return $this->hasMany('Jeffpereira\RealEstate\Models\Property\BusinessProperty');
    }

    public function sub_type(): BelongsTo
    {
        return $this->belongsTo(SubType::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ImageProperty::class);
    }

    protected function generateSlug()
    {
        if ($this->slug) {
            return $this->slug;
        }
        $slug = $this->slugBasedInContext();
        $check = Property::where('slug', $slug)->first();
        while ($check) {
            $slug = $this->slugBasedInContext() . '-' . ($this->code ? $this->code  : (static::max('code') + 1));
            $check = Property::where('slug', $slug)->first();
        }
        return $slug;
    }

    private function slugBasedInContext(): string
    {
        $subType = $this->sub_type ? $this->sub_type : SubType::find($this->sub_type_id);

        $generate = sprintf(
            "%s em %s - %s %s %s %s %s",
            // $this->business->name,
            Str::title($subType->name),
            Str::title($this->address->neighborhood->name),
            Str::title($this->address->neighborhood->city->state->initials),
            $this->max_dormitory ? $this->max_dormitory . " dormitórios," : '',
            $this->max_bathroom ? $this->max_bathroom . " banheiros," : '',
            $this->max_suite ? $this->max_suite . " suites," : '',
            $this->max_garage ? $this->max_garage . " garagens," : ''
        );
        return Str::slug(Str::limit($generate, 150));
    }

    public function generateAltImage()
    {
        return sprintf(
            "%s em %s %s %s",
            Str::title($this->sub_type->name),
            Str::title($this->address->neighborhood->name),
            Str::title($this->address->neighborhood->city->name),
            Str::title($this->address->neighborhood->city->state->name)
        );
    }
}
