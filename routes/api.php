<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

// Rutas REST: solo usuarios por ahora
Route::apiResource('users', UserController::class);

