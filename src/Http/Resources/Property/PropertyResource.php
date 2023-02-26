<?php

namespace Jeffpereira\RealEstate\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;
use Jeffpereira\RealEstate\Http\Resources\BusinessPropertyResource;
use JPAddress\Resources\AddressResource;
use JPAddress\Resources\CityResource;
use JPAddress\Resources\CountryResource;
use JPAddress\Resources\NeighborhoodResource;
use JPAddress\Resources\StateResource;

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
                'code' => $this->code,
                'useful_area' => $this->useful_area,
                'building_area' => $this->building_area,
                'total_area' => $this->total_area,
                'ground_area' => $this->ground_area,
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
                'min_restroom' => $this->min_restroom,
                'max_restroom' => $this->max_restroom,
                'embed' => $this->embed,
                'has_plate' => (bool) $this->has_plate,
                'active' => (bool) $this->active,
            ],
            'relationships' => [
                'situation' => [
                    'data' => [
                        'type' => 'situation',
                        'id' => $this->situation_id,
                    ],
                ],
                'sub_type' => [
                    'data' => [
                        'type' => 'sub_type',
                        'id' => $this->sub_type_id,
                    ],
                ],
                'address' => [
                    'data' => [
                        'type' => 'address',
                        'id' => $this->address_id,
                    ],
                ],
                'businesses' => $this->businessesProperty->map(function ($businessProperty) {
                    return [
                        'data' => [
                            'type' => 'business_property',
                            'id' => $businessProperty->id,
                        ],
                    ];
                }),
            ],
        ];
    }

    public function with($request)
    {
        $includeds = [
            new SubTypeResource($this->sub_type),
            new AddressResource($this->address),
            new NeighborhoodResource($this->address->neighborhood),
            new CityResource($this->address->neighborhood->city),
            new StateResource($this->address->neighborhood->city->state),
            new CountryResource($this->address->neighborhood->city->state->country),
        ];
        if ($this->situation) {
            array_push($includeds, new SituationResource($this->situation));
        }

        $businessesProperty = $this->businessesProperty->map(function ($businessProperty) {
            return new BusinessPropertyResource($businessProperty);
        })->toArray();
        $businesses = $this->businessesProperty->map(function ($businessProperty) {
            return new BusinessResource($businessProperty->business);
        })->toArray();
        $includeds = array_merge($includeds, $businessesProperty);
        $includeds = array_merge($includeds, $businesses);

        return [
            'included' => $includeds,
            'error' => false,
            'message' => $this->message,
        ];
    }
}
