<?php
Route::prefix(config('realestatelaravel.route.prefix-panel'))->group(function () {
    Route::group(['namespace' => 'Jeffpereira\RealEstate\Http\Controllers'], function () {
        Route::get('dasboard', 'HomeController@dashboard')->name('jp_realestate.dashboard');
        Route::get('imoveis', 'PropertyController@list')->name('jp_realestate.property.list');
    });
});
