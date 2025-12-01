<?php
namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PosteController;
use App\Http\Controllers\Api\CompanieController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
*/


Route::get('/users', [UserProfileController::class, 'index']);
Route::post('/users',[UserProfileController::class,'store']);
Route::get('/users/{id}', [UserProfileController::class, 'show']);
Route::put('/users/{id}', [UserProfileController::class, 'update']);
Route::delete('/users/{id}', [UserProfileController::class, 'destroy']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/postes', [PosteController::class, 'index']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

      // ENTREPRISES
    Route::post('/companies', [CompanieController::class, 'store']);
    Route::get('/companies', [CompanieController::class, 'index']);
    Route::delete('/companies/{id}', [CompanieController::class, 'destroy'])
        ->middleware('auth:sanctum');
    Route::put('/companies/{id}', [CompanieController::class, 'update'])
     ->middleware('auth:sanctum');


    // POSTS
    Route::post('/postes', [PosteController::class, 'store']);
    Route::get('/postes/{id}', [PosteController::class, 'userPosts']);

    // COMMENTAIRES
    Route::post('/postes/{id}/comment', [CommentController::class, 'store']);
    Route::put('/comment/{id}', [CommentController::class, 'update']);
    Route::get('/postes/{id}/comment', [CommentController::class, 'index']);



    // LIKES
    Route::post('/postes/{id}/like', [LikeController::class, 'toggleLike']);

    Route::post('/postes/{id}/like', [LikeController::class, 'like']);
    Route::delete('/postes/{id}/unlike', [LikeController::class, 'unlike']);
    Route::get('/postes/{id}/likes', [LikeController::class, 'count']);
});