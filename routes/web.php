<?php
Route::prefix(config('realestatelaravel.route.prefix-panel'))->group(function () {
    Route::group(['namespace' => 'Jeffpereira\RealEstate\Http\Controllers'], function () {
        Route::get('dasboard', 'HomeController@dashboard')->name('jp_realestate.dashboard');
        // Property
        Route::get('imoveis', 'PropertyController@list')->name('jp_realestate.property.list');
        Route::get('imoveis/new', 'PropertyController@create')->name('jp_realestate.property.create');
    });
});
