<?php

namespace Jeffpereira\RealEstate\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Jeffpereira\RealEstate\Http\Resources\Traits\ResolverEntities;

class BaseResourceCollection extends ResourceCollection
{
    use ResolverEntities;

    /** @var Collection */
    private $includes;

    public function __construct($resource, $message = '', int $code = null)
    {
        parent::__construct($resource);

        $this->includes = collect([]);

        $this->additional([
            'error' => false,
            'message' => $message
        ]);
    }

    public function with($request)
    {
        if ($request->with) $this->loadRelationships($request);

        return [
            'included' =>  $this->includes->toArray()
        ];
    }

    private function loadRelationships(Request $request): void
    {
        $relationships = $request->with ? explode(',', $request->with) : [];

        $this->collection->map->load($relationships);

        foreach ($relationships as $relationship) {
            $this->collection->pluck($relationship)->unique()->sortBy('id')->map(function ($item) {
                $resource = $this->resolverEntity(get_class($item));
                $this->includes->push(new $resource($item));
            });
        }
    }
}
