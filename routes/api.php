<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FriendController;

Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);


// Email megerősítés
Route::post('/email/verification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Megerősítő email elküldve.']);
})->middleware(['auth:sanctum'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Sikeres megerősítés.']);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');


Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    Route::get('/users', [UserController::class, 'index']);

    Route::post('/friends/{id}', [FriendController::class, 'add']);
    Route::get('/friends', [FriendController::class, 'index']);

    Route::post('/messages/{receiver}', [MessageController::class, 'send']);
    Route::get('/messages/{user}', [MessageController::class, 'conversation']);
});
