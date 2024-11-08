<?php

namespace Jeffpereira\RealEstate\Http\Resources\Property;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubTypeCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($subType) {
            return new SubTypeResource($subType);
        });
    }

    public function with($request)
    {
        return [
            'included' => new TypeCollection($this->collection->pluck('type')->unique()->values()),
        ];
    }
}
