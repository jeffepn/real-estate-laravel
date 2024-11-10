<?php

namespace Jeffpereira\RealEstate\Http\Controllers\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait Errors
{
    public function registerError(Exception $ex, $method = __METHOD__): void
    {
        Log::error(
            "Error in {$method}: " . $ex->getMessage(),
            [
                'exception' => $ex,
            ]
        );
    }
}
