<?php

use App\Http\Controllers\Gateway\AssasController;
use App\Http\Controllers\Notebook\NotebookController;
use App\Http\Controllers\User\FaqController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('webhook-assas', [AssasController::class, 'webhook'])->name('webhook-assas');

Route::post('create-ticket', [FaqController::class, 'createTicket'])->name('create-ticket');