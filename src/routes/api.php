<?php
// Controllers
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\BusinessController;

Route::middleware('api')->group(function () {
    Route::prefix('api')->group(function () {
        Route::apiResources([
            "business" => BusinessController::class
        ]);
    });
});
