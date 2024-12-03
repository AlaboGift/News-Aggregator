<?php


use App\Http\Controllers\Api\V1\NewsController;
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

Route::prefix('v1')->group(function () {
    Route::get('/articles', [NewsController::class, 'articles']);
    Route::prefix('enums')->group(function () {
        Route::get('/sources', [NewsController::class, 'sources']);
        Route::get('/categories', [NewsController::class, 'categories']);
    });
});

Route::fallback(function () {
    return response()->json([
        "app_name" => "News Aggregator API Service",
        "version" => "v1",
        "status" => false,
        "message" => "Route Not Found"
    ], 404);
});
