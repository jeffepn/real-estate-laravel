<?php

use Illuminate\Support\Facades\Route;
use Jeffpereira\RealEstate\Http\Controllers\HomeController;
use Jeffpereira\RealEstate\Http\Controllers\ProjectController;
use Jeffpereira\RealEstate\Http\Controllers\PropertyController;
use Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper;

Route::middleware(ConfigHelper::get('middleware.web'))->group(function () {
    Route::prefix('panel-realestate')->group(function () {
        Route::name('jp_realestate.')->group(function () {
            Route::get('dasboard', [HomeController::class, 'dashboard'])->name('jp_realestate.dashboard');

            Route::resource('imoveis', PropertyController::class, ['names' => 'property'])
                ->parameters(['imoveis' => 'property'])
                ->only(['index', 'create', 'edit']);

            Route::resource('projetos', ProjectController::class, ['names' => 'project'])
                ->parameters(['projetos' => 'project'])
                ->only(['index', 'create', 'edit']);

            //Banners
            //Route::get('banners', 'BannerController@list')->name('jp_realestate.banner.list');
            //Route::get('banners/edit/{banner}', 'BannerController@edit')->name('jp_realestate.banner.edit');
        });
    });
});
