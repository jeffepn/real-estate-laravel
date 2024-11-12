<?php

namespace Jeffpereira\RealEstate\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SituationResource extends JsonResource
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
            'type' => 'situation',
            'id' => $this->id,
            'attributes' => [
                'slug' => $this->slug,
                'name' => Str::title($this->name),
                'number_linked_properties' => $this->properties()->count(),
            ],
        ];
    }

    public function with($request)
    {
        return [
            'error' => false,
            'message' => $this->message,
        ];
    }
}
