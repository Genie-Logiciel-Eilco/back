<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
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
Route::post("/login", [AuthController::class, "login"]);
Route::post("/register",[AuthController::class,"register"]);
Route::get('email/verify/{id}', [VerificationController::class,"verify"])->name('verification.verify'); // Make sure to keep this as your route name
Route::post('email/resend', [VerificationController::class,"resend"])->name('verification.resend');
Route::prefix("/user")->group(function(){
    Route::post("/forgotPassword",[AuthController::class,"forgotPassword"]);
    Route::post("/resetPassword",[AuthController::class,"resetPassword"]);    
});
Route::group(["middleware" => ["auth:sanctum"]], function () {
    // Authentication routes protected
    Route::get("/logout", [AuthController::class, "logout"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get("/bruh", function () {
    return ["message" => "Bruh"];
});