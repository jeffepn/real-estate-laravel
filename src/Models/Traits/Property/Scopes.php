<?php

namespace Jeffpereira\RealEstate\Models\Traits\Property;

trait Scopes
{
    public function scopeActive($query)
    {
        return $query->where('properties.active', true);
    }

    public function scopeNotActive($query)
    {
        return $query->where('properties.active', false);
    }

    public function scopeSearch($query, $search)
    {
        $query->join('business_properties', 'business_properties.property_id', '=', 'properties.id')
            ->join('businesses', 'businesses.id', '=', 'business_properties.business_id')
            ->join('sub_types', 'sub_types.id', '=', 'properties.sub_type_id')
            ->where('properties.code', 'like', "{$search}%")
            ->orWhere('properties.min_description', 'like', "%{$search}%")
            ->orWhere('businesses.name', 'like', "%{$search}%")
            ->orWhere('sub_types.name', 'like', "%{$search}%")
            ->orWhereRaw(
                'MATCH(min_description, content) AGAINST(?)',
                [$search]
            )->orWhereRaw(
                'MATCH(businesses.name) AGAINST(?)',
                [$search]
            )->orWhereRaw(
                'MATCH(sub_types.name) AGAINST(?)',
                [$search]
            );
    }
}
