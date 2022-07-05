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
        $query->join('sub_types', 'sub_types.id', '=', 'properties.sub_type_id')
            ->join('types', 'sub_types.type_id', '=', 'types.id')
            ->leftJoin('business_properties', 'business_properties.property_id', '=', 'properties.id')
            ->leftJoin('businesses', 'businesses.id', '=', 'business_properties.business_id')
            ->where('properties.code', 'like', "{$search}%")
            ->orWhere('properties.min_description', 'like', "%{$search}%")
            ->orWhere('businesses.name', 'like', "%{$search}%")
            ->orWhere('types.name', 'like', "%{$search}%")
            ->orWhere('sub_types.name', 'like', "%{$search}%")
            ->orWhereRaw(
                'MATCH(properties.min_description, properties.content) AGAINST(?)',
                [$search]
            )->orWhereRaw(
                'MATCH(businesses.name) AGAINST(?)',
                [$search]
            )->orWhereRaw(
                'MATCH(types.name) AGAINST(?)',
                [$search]
            )->orWhereRaw(
                'MATCH(sub_types.name) AGAINST(?)',
                [$search]
            );
    }
}
