<?php

namespace Jeffpereira\RealEstate\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Jeffpereira\RealEstate\Http\Resources\BaseResource;
use Jeffpereira\RealEstate\Http\Resources\Person\PersonResource;
use Jeffpereira\RealEstate\Http\Resources\Person\TypePersonResource;
use Jeffpereira\RealEstate\Http\Resources\Traits\ResolverEntities;

class ProjectResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'project',
            'id' => $this->id,
            'attributes' => [
                'slug' => $this->slug,
                'name' => $this->name,
                'content' => $this->content,
            ],
            'relationships' => [
                'responsible' => $this->whenLoaded('responsible', [
                    'data' => [
                        'type' => 'person',
                        'id' => $this->person_id,
                    ]
                ]),
            ]
        ];
    }
}
