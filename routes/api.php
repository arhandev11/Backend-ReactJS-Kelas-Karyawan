<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
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
Route::middleware('auth:sanctum')->post("/logout", [AuthController::class, "logout"]);

Route::get("/users", [ProfileController::class, "index"]);
Route::post("/users", [ProfileController::class, "store"]);
Route::get("/users/{profile}", [ProfileController::class, "show"]);
Route::put("/users/{profile}", [ProfileController::class, "update"]);
Route::delete("/users/{profile}", [ProfileController::class, "delete"]);

Route::get("/products", [ProductController::class, "indexWithoutUser"]);
Route::get("/products/{product}", [ProductController::class, "show"]);
Route::post("/products", [ProductController::class, "storeWithoutUser"]);
Route::put("/products/{product}", [ProductController::class, "update"]);
Route::delete("/products/{product}", [ProductController::class, "delete"]);

Route::get("/quiz/products", [ProductController::class, "index"]);
Route::get("/quiz/products/{product}", [ProductController::class, "show"]);
Route::middleware('auth:sanctum')->group(function(){
    Route::post("/quiz/products", [ProductController::class, "store"]);
    Route::put("/quiz/products/{product}", [ProductController::class, "update"]);
    Route::delete("/quiz/products/{product}", [ProductController::class, "delete"]);
});

Route::get("/auth/articles", [ArticleController::class, "index"]);
Route::get("/auth/articles/{article}", [ArticleController::class, "show"]);
Route::middleware('auth:sanctum')->group(function(){
    Route::post("/auth/articles", [ArticleController::class, "store"]);
    Route::put("/auth/articles/{article}", [ArticleController::class, "update"]);
    Route::delete("/auth/articles/{article}", [ArticleController::class, "delete"]);
});

Route::get("/articles", [ArticleController::class, "indexWithoutUser"]);
Route::post("/articles", [ArticleController::class, "storeWithoutUser"]);
Route::get("/articles/{article}", [ArticleController::class, "show"]);
Route::put("/articles/{article}", [ArticleController::class, "update"]);
Route::delete("/articles/{article}", [ArticleController::class, "delete"]);


Route::prefix('/final')->group(function () {
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/register", [AuthController::class, "register"]);
    Route::middleware('auth:sanctum')->post("/logout", [AuthController::class, "logout"]);

    Route::prefix('/products')->group(function(){
        Route::get("/", [ProductController::class, "index"]);
        Route::get("/home", [ProductController::class, "home"]);
        Route::get("/{product}", [ProductController::class, "show"]);
        Route::middleware('auth:sanctum')->group(function(){
            Route::post("/", [ProductController::class, "store"]);
            Route::put("/{product}", [ProductController::class, "update"]);
            Route::delete("/{product}", [ProductController::class, "delete"]);
        });
    });

    Route::prefix('/carts')->group(function(){
        Route::middleware('auth:sanctum')->group(function(){
            Route::get("/", [CartController::class, "index"]);
            Route::post("/", [CartController::class, "store"]);
            Route::delete("/{cart}", [CartController::class, "delete"]);
        });
    });

    Route::prefix('/transactions')->group(function(){
        Route::middleware('auth:sanctum')->group(function(){
            Route::get("/", [TransactionController::class, "index"]);
            Route::post("/", [TransactionController::class, "store"]);
        });
    });
});

