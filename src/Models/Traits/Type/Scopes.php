<?php

namespace Jeffpereira\RealEstate\Models\Traits\Type;

trait Scopes
{
    public function scopeSearch($query, $search)
    {
        $query->where('name', 'like', "%{$search}%")
            ->orWhereRaw(
                'MATCH(name) AGAINST(?)',
                [$search]
            );
    }
}
