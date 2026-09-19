<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

    Route::prefix('/tag')->as('tag.')->middleware(['auth'])->group(function() {
        Route::get('/index', 'TagController@index')->name('index');
        Route::get('/create', 'TagController@create')->name('create');
        Route::post('/store', 'TagController@store')->name('store');
        Route::get('/edit/{tag}', 'TagController@edit')->name('edit');
        Route::post('/update/{tag}', 'TagController@update')->name('update');
        Route::get('/destroy/{tag}', 'TagController@destroy')->name('destroy');
    });