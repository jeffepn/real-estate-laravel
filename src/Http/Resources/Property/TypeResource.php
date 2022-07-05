<?php

namespace Jeffpereira\RealEstate\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class TypeResource extends JsonResource
{
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
            'type' => 'type',
            'id' => $this->id,
            'attributes' => [
                'slug' => $this->slug,
                'name' => Str::title($this->name),
            ],
            'relationships' => [
                'sub_types' => [
                    'data' => $this->sub_types->map(function ($subType) {
                        return [
                            'type' => 'sub_type',
                            'id' => $subType->id,
                        ];
                    }),
                ],
            ],
        ];
    }

    public function with($request)
    {
        return [
            'included' => new SubTypeCollection($this->sub_types),
            'error' => false,
            'message' => $this->message,
        ];
    }
}
