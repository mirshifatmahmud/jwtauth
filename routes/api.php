<?php

use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\ForgotPasswordController;
use App\Http\Controllers\Api\User\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/{id?}',[AuthController::class,'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/profile/me', [AuthController::class, 'profile']);
});

Route::post('forgot/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::get('reset/email/{token}', [ResetPasswordController::class, 'reset'])->name('password.reset');
