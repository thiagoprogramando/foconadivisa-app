<?php

use App\Http\Controllers\Gateway\AssasController;
use App\Http\Controllers\Notebook\NotebookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('webhook-assas', [AssasController::class, 'webhook'])->name('webhook-assas');