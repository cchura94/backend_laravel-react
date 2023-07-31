<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
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

// buscar categoria

Route::middleware('auth:sanctum')->group(function(){
    
    Route::get("categoria/busqueda", [CategoriaController::class, "buscarCategoria"]);
    Route::get("producto/{id}/actualizar-imagen", [ProductoController::class, "actualizarImagen"]);
    
    // CRUD API
    Route::apiResource("categoria", CategoriaController::class);
    Route::apiResource("producto", ProductoController::class);
    

});

