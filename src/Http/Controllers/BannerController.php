<?php

namespace Jeffpereira\RealEstate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function list(): View
    {
        return view('jpviews::banners.list');
    }
}
