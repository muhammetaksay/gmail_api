<?php

use App\Http\Controllers\HomeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('auth', [HomeController::class, 'index']);
// Route::post('survey/create', [SurveyController::class, 'store']);
// Route::post('survey/update/{id}', [SurveyController::class, 'update']);
// Route::delete('survey/delete/{id}', [SurveyController::class, 'destroy']);