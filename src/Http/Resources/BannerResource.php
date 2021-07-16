<?php

namespace Jeffpereira\RealEstate\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'type' => 'banner',
            'id' => $this->id,
            'attributes' => [
                'way' => $this->way,
                'wayUrl' => $this->wayUrl,
                'title' => $this->title,
                'content' => $this->content,
                'link' => $this->link,
            ]
        ];
    }
}
