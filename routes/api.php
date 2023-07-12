<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// rutas auth
Route::prefix("v1/auth")->group(function(){

    Route::post("login", [AuthController::class, "funIngresar"]);
    Route::post("registro", [AuthController::class, "funRegistrar"]);

    Route::middleware('auth:sanctum')->group(function(){
        
        Route::get("perfil", [AuthController::class, "funPerfil"]);
        Route::post("salir", [AuthController::class, "funSalir"]);
    });
});
