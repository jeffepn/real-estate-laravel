<?php

namespace Jeffpereira\RealEstate\Http\Resources\Person;

use Jeffpereira\RealEstate\Http\Resources\BaseResourceCollection;

class PersonCollection extends BaseResourceCollection
{
    public $collects = PersonResource::class;
}
