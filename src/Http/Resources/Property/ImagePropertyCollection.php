<?php

namespace Jeffpereira\RealEstate\Http\Resources\Property;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ImagePropertyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($imageProperty) {
            return new ImagePropertyResource($imageProperty);
        });
    }
}
