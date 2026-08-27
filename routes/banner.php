<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('/banner')->as('banner.')->middleware(['auth'])->group(function() {
    Route::get('/index', 'BannerController@index')->name('index');
    Route::get('/create', 'BannerController@create')->name('create');
    Route::post('/store', 'BannerController@store')->name('store');
    Route::get('/edit/{banner}', 'BannerController@edit')->name('edit');
    Route::post('/update/{banner}', 'BannerController@update')->name('update');
    Route::get('/destroy/{banner}', 'BannerController@destroy')->name('destroy');
});
