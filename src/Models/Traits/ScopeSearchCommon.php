<?php

namespace Jeffpereira\RealEstate\Models\Traits;

trait ScopeSearchCommon
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
