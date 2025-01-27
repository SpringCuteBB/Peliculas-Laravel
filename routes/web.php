<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Controller;
use App\Http\Controllers\MovieController;

Route::get('/', [MovieController::class, 'index']) -> name('index');
Route::post('/add-movie', [MovieController::class, 'store']);
Route::delete('/remove-movie/{id}', [MovieController::class,'destroy']);
Route::get('/search-movie/{search?}/{gendre?}/{minAge?}/{maxAge?}', action: [MovieController::class, 'search']);
Route::get ('/show-all-movies' , action: [MovieController::class,'showAllMovies']);