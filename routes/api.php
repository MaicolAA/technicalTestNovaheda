<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NoteController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('contacts', ContactController::class);

    Route::apiResource('notes', NoteController::class);

    Route::get('/companies/{company}/notes', [NoteController::class, 'getNotesForCompany']);
    Route::get('/contacts/{contact}/notes', [NoteController::class, 'getNotesForContact']);
});
