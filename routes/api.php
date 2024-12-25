<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::post('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('auth/register',[App\Http\Controllers\System\AuthController::class,'register'])->name('api.auth-register');
Route::post('auth/login',[App\Http\Controllers\System\AuthController::class,'login'])->name('api.auth-login');

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'check-role:Admin'], 'as' => 'admin-'], function (){

    Route::post('logout',[App\Http\Controllers\System\AuthController::class,'logout'])->name('api.user-logout');

    Route::get('manage/movies',[App\Http\Controllers\Manager\MoviesController::class, 'index'])->name('api.manage.movies-index');
    Route::post('manage/movies/store',[App\Http\Controllers\Manager\MoviesController::class, 'store'])->name('api.manage.movies-store');
    Route::post('manage/movies/{id}/show',[App\Http\Controllers\Manager\MoviesController::class, 'show'])->name('api.manage.movies-show');
    Route::post('manage/movies/{id}/rating',[App\Http\Controllers\Manager\ReviewsController::class, 'index'])->name('api.manage.movies-rating');
    Route::patch('manage/movies/{id}/update',[App\Http\Controllers\Manager\MoviesController::class, 'update'])->name('api.manage.movies-update');
    Route::delete('manage/movies/{id}/delete',[App\Http\Controllers\Manager\MoviesController::class, 'destroy'])->name('api.manage.movies-delete');

});

Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum', 'check-role:Member'], 'as' => 'user-'], function (){

    Route::post('logout',[App\Http\Controllers\System\AuthController::class,'logout'])->name('api.user-logout');

    Route::get('movies',[App\Http\Controllers\Manager\MoviesController::class, 'index'])->name('api.movies-index');
    Route::get('movies/{id}/show',[App\Http\Controllers\Manager\MoviesController::class, 'show'])->name('api.movies-show');
    
    Route::get('movies/{id}/rating',[App\Http\Controllers\Manager\ReviewsController::class, 'index'])->name('api.movies-rating-index');
    Route::post('movies/{id}/rating/store',[App\Http\Controllers\Manager\ReviewsController::class, 'store'])->name('api.movies-rating-store');
    Route::patch('movies/{id}/rating/{id_rating}/update',[App\Http\Controllers\Manager\ReviewsController::class, 'update'])->name('api.movies-rating-update');
    Route::delete('movies/{id}/rating/{id_rating}/delete',[App\Http\Controllers\Manager\ReviewsController::class, 'destroy'])->name('api.movies-rating-delete');

});
