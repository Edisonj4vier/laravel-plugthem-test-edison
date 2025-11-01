<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SurveyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Rutas públicas de autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas que requieren autenticación con Sanctum
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('surveys', SurveyController::class);
    Route::apiResource('surveys.questions', QuestionController::class)->shallow();
    Route::post('/surveys/{survey}/answer', [AnswerController::class, 'store']);
    //Route::get('/reports/survey/{survey}', [ReportController::class, 'show']);

});

