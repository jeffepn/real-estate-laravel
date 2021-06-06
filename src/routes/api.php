<?php
// Controllers
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\BusinessController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\PropertyController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\SubTypeController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\TypeController;

Route::middleware('api')->group(function () {
    Route::prefix('api')->group(function () {
        Route::apiResources([
            "business" => BusinessController::class,
            "type" => TypeController::class,
            "sub-type" => SubTypeController::class,
            "property" => PropertyController::class,
        ]);
    });
});
