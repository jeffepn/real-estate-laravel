<?php

use Jeffpereira\RealEstate\Http\Controllers\Api\AppSettingController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\BusinessController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\PropertyController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\SubTypeController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\TypeController;
use Jeffpereira\RealEstate\Http\Controllers\Api\BannerController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\ImagePropertyController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Property\SituationController;
use Illuminate\Support\Facades\Route;
use Jeffpereira\RealEstate\Http\Controllers\Api\Person\PersonController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Person\TypePersonController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Project\ImageProjectController;
use Jeffpereira\RealEstate\Http\Controllers\Api\Project\ProjectController;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;

Route::middleware(ConfigHelper::get('middleware.api'))->group(function () {
    Route::prefix('api')->group(function () {
        Route::name('jp_realestate.api.')->group(function () {
            Route::apiResources([
                "business" => BusinessController::class,
                "situation" => SituationController::class,
                "type" => TypeController::class,
                "sub_type" => SubTypeController::class,
                "property" => PropertyController::class,
                "image_property" => ImagePropertyController::class,
                "project" => ProjectController::class,
                "image_project" => ImageProjectController::class,
                "banner" => BannerController::class,
                "person" => PersonController::class,
                "type_person" => TypePersonController::class,
                "app_setting" => AppSettingController::class,
            ]);
            Route::patch("property/{property}/active_or_inactive", [PropertyController::class, "activeOrInactive"])->name('property.active_or_inactive');
            Route::patch("image-property/update-order", [ImagePropertyController::class, "updateOrder"])->name('image_property.update_order');
        });
    });
});
