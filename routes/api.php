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
    // Book Controller
    Route::post("/book/uploadImage/{uuid?}",[BookController::class,"uploadImage"]);
    Route::post("/book/uploadFile/{uuid?}",[BookController::class,"uploadFile"]);
    Route::post("/book/add/{uuid}",[BookController::class,"addBook"]);
    Route::get("/book/{uuid}",[BookController::class,"show"]);
    Route::get("/books/paginate/{rowsPerPage?}",[BookController::class,"paginate"]);
    Route::get("/books",[BookController::class,"getAll"]);
    Route::delete('/book/{uuid}',[BookController::class,"delete"]);
    // Author Controller
    Route::get("/author/{id}",[AuthorController::class,"getById"]);
    Route::get("/authors",[AuthorController::class,"getAll"]);
    Route::get("/authors/paginate/{rowsPerPage?}",[AuthorController::class,"paginate"]);
    Route::post("/author/add",[AuthorController::class,"add"]);
    Route::post("/author/update/{id}",[AuthorController::class,"update"]);
    Route::delete("/author/{id}",[AuthorController::class,"delete"]);
    // Publisher Controller
    Route::get("/publisher/{id}",[PublisherController::class,"getById"]);
    Route::get("/publishers",[PublisherController::class,"getAll"]);
    Route::get("/publishers/paginate/{rowsPerPage?}",[PublisherController::class,"paginate"]);
    Route::post("/publisher/add",[PublisherController::class,"add"]);
    Route::post("/publisher/update/{id}",[PublisherController::class,"update"]);
    Route::delete("/publisher/{id}",[PublisherController::class,"delete"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get("/bruh", function () {
    return ["message" => "Bruh"];
});