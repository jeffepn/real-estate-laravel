<?php

namespace Jeffpereira\RealEstate\Http\Resources\Property;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($type) {
            return new TypeResource($type);
        });
    }

    public function with($request)
    {
        return [
            'included' => new SubTypeCollection(
                $this->collection->pluck('sub_types')
                    ->flatten()
                    ->unique()
                    ->values()
                    ->sortBy("name")
            )
        ];
    }
}
