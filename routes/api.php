<?php
// Controllers
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\BusinessController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\PropertyController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\SubTypeController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\TypeController;

Route::middleware(config('realestatelaravel.middleware.api'))->group(function () {
    Route::prefix('api')->group(function () {
        Route::name('jp_realestate.')->group(function () {
            Route::apiResources([
                "business" => BusinessController::class,
                "type" => TypeController::class,
                "sub-type" => SubTypeController::class,
                "property" => PropertyController::class,
            ]);
            Route::get("property/{property}/image-property", [PropertyController::class, "indexImage"])->name('property.index-image_property');
            Route::post("image-property", [PropertyController::class, "uploadImage"])->name('image_property.store');
            Route::delete("image-property/{imageProperty}", [PropertyController::class, "destroyImage"])->name('image_property.destroy');
        });
    });
});
