<?php

namespace Jeffpereira\RealEstate\Models\Traits\Person;

trait Scopes
{
    public function scopeSearch($query, $search)
    {
        $query->join('type_people', 'people.type_person_id', '=', 'type_people.id')
            ->where('people.name', 'like', "%{$search}%")
            ->orWhere('people.bio', 'like', "%{$search}%")
            ->orWhere('type_people.name', 'like', "%{$search}%")
            ->orWhereRaw(
                'MATCH(people.name, people.bio) AGAINST(?)',
                [$search]
            )->orWhereRaw(
                'MATCH(type_people.name) AGAINST(?)',
                [$search]
            );
    }
}
