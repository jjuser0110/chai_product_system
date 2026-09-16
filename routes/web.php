<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\FrontendController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/', [FrontendController::class, 'index'])
    ->name('frontend.home');
Auth::routes();
Route::get('/product/{product}', [FrontendController::class, 'product'])
    ->name('frontend.product');
Route::get('/categories', [FrontendController::class, 'categories'])
    ->name('frontend.categories');
Route::get('/highlights', [FrontendController::class, 'highlights'])
    ->name('frontend.highlights');
Route::get('/contact', [FrontendController::class, 'contact'])
    ->name('frontend.contact');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/change_password', [App\Http\Controllers\HomeController::class, 'change_password'])->name('change_password');
Route::get('/removeimage/{image_id}', 'HomeController@removeimage')->name('removeimage');