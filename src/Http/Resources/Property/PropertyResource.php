<?php

namespace Jeffpereira\RealEstate\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;
use JPAddress\Resources\AddressResource;
use JPAddress\Resources\CityResource;
use JPAddress\Resources\NeighborhoodResource;

class PropertyResource extends JsonResource
{
    /**
     * Message response
     *
     * @var string
     */
    private $message;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $message = '')
    {
        parent::__construct($resource);
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'property',
            'id' => $this->id,
            'attributes' => [
                'slug' => $this->slug,
                'building_area' => $this->building_area,
                'total_area' => $this->total_area,
                'min_description' => $this->min_description,
                'content' => $this->content,
                'items' => $this->items,
                'min_dormitory' => $this->min_dormitory,
                'max_dormitory' => $this->max_dormitory,
                'min_bathroom' => $this->min_bathroom,
                'max_bathroom' => $this->max_bathroom,
                'min_suite' => $this->min_suite,
                'max_suite' => $this->max_suite,
                'min_garage' => $this->min_garage,
                'max_garage' => $this->max_garage,
            ],
            'relationships' => [
                'sub_type' => [
                    'data' => [
                        'type' => 'sub_type',
                        'id' => $this->sub_type->id,
                    ]
                ],
                'business' => [
                    'data' => [
                        'type' => 'business',
                        'id' => $this->business->id,
                    ]
                ],
                'address' => [
                    'data' => [
                        'type' => 'address',
                        'id' => $this->address->id,
                    ]
                ],
            ]
        ];
    }
    public function with($request)
    {
        return [
            'included' => [
                new SubTypeResource($this->sub_type),
                new BusinessResource($this->business),
                new AddressResource($this->address),
                new NeighborhoodResource($this->address->neighborhood),
                new CityResource($this->address->neighborhood->city),
            ],
            'error' => false,
            'message' => $this->message
        ];
    }
}
