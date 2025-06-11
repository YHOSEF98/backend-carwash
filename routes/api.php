<?php

use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\empresaController;
use App\Http\Controllers\Api\TiposVehiculosController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\ClienteController;
use App\Http\Controllers\api\DetalleProductoController;
use App\Http\Controllers\api\DetalleServicioController;
use App\Http\Controllers\api\PrecioServicioController;
use App\Http\Controllers\api\ProductoController;
use App\Http\Controllers\Api\TiposServiciosController;
use App\Http\Controllers\api\VentaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/usuarios', [AuthController::class, 'index']);
Route::post('login', [AuthController::class, 'login']);
Route::apiResource('empresas', empresaController::class);

Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::apiResource('empleados', EmpleadoController::class);
    Route::apiResource('tipos-vehiculos', TiposVehiculosController::class);
    Route::apiResource('tipos-servicios', TiposServiciosController::class);
    Route::apiResource('precios-servicios', PrecioServicioController::class);
    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('ventas', VentaController::class);
    Route::apiResource('detalle-servicios', DetalleServicioController::class);
    Route::apiResource('detalle-productos', DetalleProductoController::class);

});
