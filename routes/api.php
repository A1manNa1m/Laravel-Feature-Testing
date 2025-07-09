<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TagController;
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

// api/??
Route::namespace('App\Http\Controllers')->group(function () {
    Route::resource('country', CountryController::class);
    Route::resource('comment', CommentController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('project', ProjectController::class);
    Route::resource('proposal', ProposalController::class);
    Route::resource('skill', SkillController::class);
    Route::resource('tag', TagController::class);
});
