<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('/productt')->as('productt.')->middleware(['auth'])->group(function() {
    Route::get('/index', 'ProducttController@index')->name('index');
    Route::get('/create', 'ProducttController@create')->name('create');
    Route::post('/store', 'ProducttController@store')->name('store');
    Route::get('/edit/{product}', 'ProducttController@edit')->name('edit');
    Route::post('/update/{product}', 'ProducttController@update')->name('update');
    Route::get('/destroy/{product}', 'ProducttController@destroy')->name('destroy');
});
