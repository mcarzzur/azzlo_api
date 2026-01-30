<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HabitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;

// Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [PasswordResetController::class, 'sendCode']);


// Rutas protegidas por token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/habits', [HabitController::class, 'index']);
    Route::post('/habits', [HabitController::class, 'store']);
    Route::put('/habits/{id}', [HabitController::class, 'update']);
    Route::delete('/habits/{id}', [HabitController::class, 'destroy']);
    Route::post('/habits/{id}/complete', [HabitController::class, 'complete']);
});


