<?php

namespace Jeffpereira\RealEstate\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusinessPropertyResource extends JsonResource
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
            'type' => 'business_property',
            'id' => $this->id,
            'attributes' => [
                'property_id' => $this->property_id,
                'business_id' => $this->business_id,
                'value' => $this->value,
                'status' => $this->status,
                'status_situation' => $this->status_situation,
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
