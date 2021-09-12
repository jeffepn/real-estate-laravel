<?php
// Controllers
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\BusinessController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\PropertyController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\SubTypeController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\TypeController;
use Jeffpereira\RealEstate\Http\Controllers\Api\BannerController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\ImagePropertyController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\SituationController;

Route::middleware(config('realestatelaravel.middleware.api'))->group(function () {
    Route::prefix('api')->group(function () {
        Route::name('jp_realestate.')->group(function () {
            Route::apiResources([
                "business" => BusinessController::class,
                "situation" => SituationController::class,
                "type" => TypeController::class,
                "sub-type" => SubTypeController::class,
                "property" => PropertyController::class,
                "image_property" => ImagePropertyController::class,
                "banner" => BannerController::class,
            ]);
            Route::patch("property/{property}/active_or_inactive", [PropertyController::class, "activeOrInactive"])->name('property.active_or_inactive');
            Route::patch("image-property/update-order", [ImagePropertyController::class, "updateOrder"])->name('image_property.update_order');
        });
    });
});
