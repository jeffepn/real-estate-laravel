<?php

namespace Jeffpereira\RealEstate\Http\Resources\Property;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SituationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($situation) {
            return new SituationResource($situation);
        });
    }
}
