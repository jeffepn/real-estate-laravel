<?php
Route::middleware(config('realestatelaravel.middleware.web'))->group(function () {
    Route::prefix('panel-realestate')->group(function () {
        Route::group(['namespace' => 'Jeffpereira\RealEstate\Http\Controllers'], function () {
            Route::get('dasboard', 'HomeController@dashboard')->name('jp_realestate.dashboard');
            // Property
            Route::get('imoveis', 'PropertyController@list')->name('jp_realestate.property.list');
            Route::get('imoveis/new', 'PropertyController@create')->name('jp_realestate.property.create');
            Route::get('imoveis/edit/{property}', 'PropertyController@edit')->name('jp_realestate.property.edit');
        });
    });
});
