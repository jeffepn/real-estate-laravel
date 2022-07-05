<?php

namespace Jeffpereira\RealEstate\Models\Traits\Project;

trait Scopes
{
    public function scopeSearch($query, $search)
    {
        $query->join('people', 'projects.person_id', '=', 'people.id')
            ->join('type_people', 'people.type_person_id', '=', 'type_people.id')
            ->where('projects.name', 'like', "%{$search}%")
            ->orWhere('projects.content', 'like', "%{$search}%")
            ->orWhere('people.name', 'like', "%{$search}%")
            ->orWhere('people.bio', 'like', "%{$search}%")
            ->orWhere('type_people.name', 'like', "%{$search}%")
            ->orWhereRaw(
                'MATCH(projects.name, projects.content) AGAINST(?)',
                [$search]
            )
            ->orWhereRaw(
                'MATCH(people.name, people.bio) AGAINST(?)',
                [$search]
            )->orWhereRaw(
                'MATCH(type_people.name) AGAINST(?)',
                [$search]
            );
    }
}
