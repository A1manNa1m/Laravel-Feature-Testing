<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\CommentController;
use App\Http\Controllers\API\V1\CountryController;
use App\Http\Controllers\API\V1\ProfileController;
use App\Http\Controllers\API\V1\ProjectController;
use App\Http\Controllers\API\V1\ProposalController;
use App\Http\Controllers\API\V1\SkillController;
use App\Http\Controllers\API\V1\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Public Routes (no auth needed)
Route::prefix('v1')->namespace('App\Http\Controllers\API\V1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});


// Protected Routes (require auth:sanctum)
//api/v1
Route::group(['prefix'=>'v1', 'namespace'=>'App\Http\Controllers\API\V1','middleware'=>'auth:sanctum'], function(){
    Route::resource('country', CountryController::class);
    Route::resource('comment', CommentController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('project', ProjectController::class);
    Route::resource('proposal', ProposalController::class);
    Route::resource('skill', SkillController::class);
    Route::resource('tag', TagController::class);

    Route::post('/logout', [AuthController::class, 'logout']);
    //Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
});


//SIMPLE WAY TO MAKE ROUTE, No need v1,v2 and etc
// api/??
// Route::namespace('App\Http\Controllers')->group(function () {
//     Route::resource('country', CountryController::class);
//     Route::resource('comment', CommentController::class);
//     Route::resource('profile', ProfileController::class);
//     Route::resource('project', ProjectController::class);
//     Route::resource('proposal', ProposalController::class);
//     Route::resource('skill', SkillController::class);
//     Route::resource('tag', TagController::class);
// });
