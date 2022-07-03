<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait Errors
{

    public function registerError(Exception $ex): void
    {
        $controller = __CLASS__;
        $method = __METHOD__;
        Log::error(
            "Error in method {$method} of {$controller}",
            [$ex->getTraceAsString()]
        );
    }
}
