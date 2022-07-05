<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait Errors
{
    public function registerError(Exception $ex, $method = __METHOD__): void
    {
        Log::error(
            "Error in {$method}",
            [
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ]
        );
    }
}
