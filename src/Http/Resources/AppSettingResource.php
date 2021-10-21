<?php

namespace Jeffpereira\RealEstate\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AppSettingResource extends JsonResource
{
    const PREFIX_METHOD_RESOURCE = "formatValue";
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
        $nameMethod = self::PREFIX_METHOD_RESOURCE . Str::studly($this->name ?? 'name_method_not_found');
        return [
            'type' => 'app_setting',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'value' => method_exists($this, $nameMethod)
                    ? $this->$nameMethod($this->value)
                    : $this->value,
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

    private function formatValueWattermarkImageProperty(array $value): array
    {
        return [
            'image' => Storage::disk(config('realestatelaravel.filesystem.disk'))->url($value['image'])
        ];
    }
}
