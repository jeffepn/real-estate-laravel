<?php

namespace Jeffpereira\RealEstate\Http\Resources\Project;

use Jeffpereira\RealEstate\Http\Resources\BaseResource;

class ImageProjectResource extends BaseResource
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
            'type' => 'image_project',
            'id' => $this->id,
            'attributes' => [
                'way' => $this->image->way_url,
                'alt' => $this->image->alt,
                'title' => $this->image->title,
                'description' => $this->image->description,
                'author' => $this->image->author,
            ],
            'relationships' => [],
        ];
    }
}
