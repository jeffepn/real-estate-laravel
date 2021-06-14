<?php

namespace Jeffpereira\RealEstate\Http\Controllers;

use Jeffpereira\RealEstate\Models\Property\Property;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Http\Requests\Property\PropertyRequest;
use Jeffpereira\RealEstate\Http\Resources\Property\PropertyCollection;
use Jeffpereira\RealEstate\Http\Resources\Property\PropertyResource;
use Jeffpereira\RealEstate\Utilities\Terminologies;
use JPAddress\Models\Address\Address;
use JPAddress\Models\Address\City;
use JPAddress\Models\Address\Country;
use JPAddress\Models\Address\Neighborhood;
use JPAddress\Models\Address\State;
use PHPUnit\Framework\Constraint\Count;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(): View
    {
        return view('jpviews::properties.list');
    }
}
