<?php

namespace Jeffpereira\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Jeffpereira\RealEstate\Http\Resources\Traits\ResolverEntities;

abstract class BaseResource extends JsonResource
{
    use ResolverEntities;

    /** @var Collection */
    public $includes;

    /** @var array */
    protected $relationships;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $message = '', int $code = null)
    {
        parent::__construct($resource);
        $this->includes = collect([]);
        $this->relationships = [];

        if ($code) $this->response()->setStatusCode($code);

        $this->additional([
            'error' => false,
            'message' => $message
        ]);
    }

    public function with($request)
    {
        $this->loadRelationships($request);
        $this->mountIncludes();

        return [
            'included' => $this->includes->unique()->toArray()
        ];
    }

    public function loadRelationships(Request $request): void
    {
        if (!$request->with) return;
        $this->relationships = explode(',', $request->with);
        $this->resource->loadMissing($this->relationships);
    }

    public function mountIncludes(): void
    {
        foreach ($this->relationships as $relationship) {
            $currentResource = $this->resource;
            $dots = explode('.', $relationship);

            foreach ($dots as $dot) $currentResource = $currentResource->{$dot};

            $classOfResource = $this->resolverEntity(get_class($currentResource));
            $this->includes->push(new $classOfResource($currentResource));
        }
    }
}
