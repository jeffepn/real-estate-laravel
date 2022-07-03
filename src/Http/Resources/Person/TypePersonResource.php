<?php

namespace Jeffpereira\RealEstate\Http\Resources\Person;

use Jeffpereira\RealEstate\Http\Resources\BaseResource;

class TypePersonResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'type' => 'type_person',
            'id' => $this->id,
            'attributes' => [
                'slug' => $this->slug,
                'name' => $this->name,
            ],
            'relationships' => []
        ];
    }
}
