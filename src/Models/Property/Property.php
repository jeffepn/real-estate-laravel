<?php

namespace Jeffpereira\RealEstate\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        self::creating(function ($model) {
            $model->code = $model->max('code') + 1;
        });
    }

    // Relationships
    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany('Jeffpereira\RealEstate\Models\Property\Business')
            ->using('Jeffpereira\RealEstate\Models\Property\BusinessProperty')
            ->withPivot([
                'value',
            ]);
    }

    public function sub_type(): BelongsTo
    {
        return $this->belongsTo(SubType::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    protected function generateSlug()
    {
        if ($this->slug) {
            return $this->slug;
        }
        $slug = $this->slugBasedInContext();
        $check = Property::where('slug', $slug)->first();
        while ($check) {
            $slug = $this->slugBasedInContext() . '-' . time() . rand(0, 9);
            $check = Property::where('slug', $slug)->first();
        }
        return $slug;
    }

    private function slugBasedInContext(): string
    {
        $generate = sprintf(
            "%s em %s - %s %s %s %s %s",
            // $this->business->name,
            Str::title($this->sub_type->name),
            Str::title($this->address->neighborhood->name),
            Str::title($this->address->neighborhood->city->state->name),
            $this->max_dormitory ? "$this->max_dormitory dormitório(s)," : '',
            $this->max_bathroom ? "$this->max_bathroom banheiro(s)," : '',
            $this->max_suite ? "$this->max_suite suite(s)," : '',
            $this->max_garage ? "$this->max_garage garagem(ns)," : '',
        );
        return Str::slug(Str::limit($generate, 150));
    }
}
