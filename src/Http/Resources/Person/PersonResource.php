<?php

namespace Jeffpereira\RealEstate\Http\Resources\Person;

use Jeffpereira\RealEstate\Http\Resources\BaseResource;

class PersonResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'type' => 'person',
            'id' => $this->id,
            'attributes' => [
                'slug' => $this->slug,
                'name' => $this->name,
                'bio' => $this->bio,
            ],
            'relationships' => [
                'type' => $this->whenLoaded('type', [
                    'data' => [
                        'type' => 'type_person',
                        'id' => $this->type_person_id,
                    ]
                ])
            ]
        ];
    }
}
