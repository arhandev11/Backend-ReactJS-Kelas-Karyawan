<?php

use App\Http\Controllers\ArticleController;
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

Route::get("/users", [ProfileController::class, "index"]);
Route::post("/users", [ProfileController::class, "store"]);
Route::get("/users/{profile}", [ProfileController::class, "show"]);
Route::put("/users/{profile}", [ProfileController::class, "update"]);
Route::delete("/users/{profile}", [ProfileController::class, "delete"]);

Route::get("/products", [ProductController::class, "index"]);
Route::post("/products", [ProductController::class, "store"]);
Route::delete("/products/{product}", [ProductController::class, "delete"]);

Route::get("/articles", [ArticleController::class, "index"]);
Route::post("/articles", [ArticleController::class, "store"]);
Route::get("/articles/{article}", [ArticleController::class, "show"]);
Route::put("/articles/{article}", [ArticleController::class, "update"]);
Route::delete("/articles/{article}", [ArticleController::class, "delete"]);
