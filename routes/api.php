<?php

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

Route::middleware('auth:sanctum')->get('/dictionary', [\App\Http\Controllers\DictionaryController::class, 'getDictionaries']);
Route::middleware('auth:sanctum')->post('/dictionary', [\App\Http\Controllers\DictionaryController::class,'createDictionary']);
Route::middleware('auth:sanctum')->put('/dictionary', [\App\Http\Controllers\DictionaryController::class,'editDictionary']);
Route::middleware('auth:sanctum')->delete('/dictionary/{id}', [\App\Http\Controllers\DictionaryController::class,'deleteDictionary']);

Route::middleware('auth:sanctum')->get('/word/{id}', [\App\Http\Controllers\WordController::class,'getWordForDictionary']);
Route::middleware('auth:sanctum')->post('/word', [\App\Http\Controllers\WordController::class,'createWord']);
Route::middleware('auth:sanctum')->put('/word', [\App\Http\Controllers\WordController::class,'editWord']);
Route::middleware('auth:sanctum')->delete('/word/{id}', [\App\Http\Controllers\WordController::class,'deleteWord']);

Route::middleware('auth:sanctum')->get('/error_word', [\App\Http\Controllers\ErrorWordController::class,'getErrorsWords']);
Route::middleware('auth:sanctum')->post('/error_word', [\App\Http\Controllers\ErrorWordController::class,'addErrorWord']);
Route::middleware('auth:sanctum')->delete('/error_word/{id}', [\App\Http\Controllers\ErrorWordController::class,'deleteErrorWord']);

Route::middleware('auth:sanctum')->get('/result_testing', [\App\Http\Controllers\ResultTestingController::class,'getResultsTesting']);
Route::middleware('auth:sanctum')->post('/result_testing', [\App\Http\Controllers\ResultTestingController::class,'createResultTesting']);




Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'createUser']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'loginUser']);



