<?php

namespace Jeffpereira\RealEstate\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Jeffpereira\RealEstate\Http\Controllers\Controller;
use Jeffpereira\RealEstate\Utilities\Terminologies;

class HomeController extends Controller
{
    /**
     * Display view dashboar.
     */
    public function dashboard(): View
    {

        return view('jprealestate::dashboard');
    }
}
