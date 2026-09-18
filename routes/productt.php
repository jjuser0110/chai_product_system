<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::prefix('/productt')->as('productt.')->middleware(['auth'])->group(function() {
    Route::get('/index', 'ProducttController@index')->name('index');
    Route::get('/create', 'ProducttController@create')->name('create');
    Route::post('/store', 'ProducttController@store')->name('store');
    Route::get('/edit/{productt}', 'ProducttController@edit')->name('edit');
    Route::post('/update/{productt}', 'ProducttController@update')->name('update');
    Route::get('/destroy/{productt}', 'ProducttController@destroy')->name('destroy');
});
