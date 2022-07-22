<?php

use App\Http\Controllers\Api\AccessController as ApiAccessController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CourseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {
//     Route::post('login', [ApiAccessController::class, 'login'])->name('login');
//     Route::post('logout', [ApiAccessController::class, 'logout'])->name('logout');
//     Route::post('register', [ApiAccessController::class, 'register'])->name('register');
//     Route::post('me', [ApiAccessController::class, 'me']);
   
//     Route::middleware('checkRole')->group(function () {
//         Route::resource('user', UserController::class);
//         Route::resource('course', CourseController::class);
//     });
// });

Route::post('login', [ApiAccessController::class, 'login'])->name('login');
Route::middleware('auth:api',)->group(function () {
    Route::post('logout', [ApiAccessController::class, 'logout'])->name('logout');
    Route::post('register', [ApiAccessController::class, 'register'])->name('register');
    Route::post('me', [ApiAccessController::class, 'me'])->name('me');
   
    Route::middleware('checkRole')->group(function () {
        Route::resource('user', UserController::class)->except('storeUserCourse','destroyUserCourse','sortUsers','searchUser');
        Route::resource('course', CourseController::class);
        Route::post('user/storeUserCourse', [ UserController::class, 'storeUserCourse'])->name('user.storeUserCourse');
        Route::delete('user/destroyUserCourse/{user}', [ UserController::class, 'destroyUserCourse'])->name('user.destroyUserCourse');
    });
});