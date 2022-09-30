<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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
Route::post("/register", [AuthController::class, "register"]);
Route::middleware('auth:sanctum')->get("/profile", [AuthController::class, "profile"]);

Route::get("/users", [ProfileController::class, "index"]);
Route::post("/users", [ProfileController::class, "store"]);
Route::get("/users/{profile}", [ProfileController::class, "show"]);
Route::put("/users/{profile}", [ProfileController::class, "update"]);
Route::delete("/users/{profile}", [ProfileController::class, "delete"]);

Route::get("/products", [ProductController::class, "index"]);
Route::get("/products/{product}", [ProductController::class, "show"]);
Route::middleware('auth:sanctum')->group(function(){
    Route::post("/products", [ProductController::class, "store"]);
    Route::put("/products/{product}", [ProductController::class, "update"]);
    Route::delete("/products/{product}", [ProductController::class, "delete"]);
});

Route::get("/articles", [ArticleController::class, "index"]);
Route::post("/articles", [ArticleController::class, "store"]);
Route::get("/articles/{article}", [ArticleController::class, "show"]);
Route::put("/articles/{article}", [ArticleController::class, "update"]);
Route::delete("/articles/{article}", [ArticleController::class, "delete"]);
