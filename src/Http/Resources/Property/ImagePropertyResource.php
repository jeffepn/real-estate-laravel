<?php

namespace Jeffpereira\RealEstate\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImagePropertyResource extends JsonResource
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
            'type' => 'image-property',
            'id' => $this->id,
            'attributes' => [
                'way' => Storage::disk(config('realestatelaravel.filesystem.disk'))->url($this->way),
                'alt' => $this->alt,
            ]
        ];
    }
    public function with($request)
    {
        return [
            'error' => false,
            'message' => $this->message
        ];
    }
}
