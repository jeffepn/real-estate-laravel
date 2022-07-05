<?php

namespace Jeffpereira\RealEstate\Http\Resources\Project;

use Jeffpereira\RealEstate\Http\Resources\BaseResource;

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
                    ],
                ]),
            ],
        ];
    }
}
