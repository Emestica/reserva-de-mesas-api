<?php

use App\Http\Controllers\RolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/obtener-roles', [RolesController::class, 'show']);
Route::post('/guardar-rol', [RolesController::class, 'store']);
Route::put('/actualizar-rol', [RolesController::class, 'update']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/** TABLA MENU */
Route::get('/get-menus',
    [\App\Http\Controllers\MenuController::class, 'getMenu']);

/** TABLA CLASIFICACIONES */
Route::get('/get-clasificaciones',
    [\App\Http\Controllers\ClasificacionController::class, 'getClasificaciones']);
Route::post('/save-clasificacion',
    [\App\Http\Controllers\ClasificacionController::class, 'storeClasificacion']);
Route::put('/update-clasificacion/{id}',
    [\App\Http\Controllers\ClasificacionController::class, 'updateClasificacion']);
Route::put('/delete-clasificacion/{id}',
    [\App\Http\Controllers\ClasificacionController::class, 'deleteClasificacion']);

/** TABLA TIPO_MENU */
Route::get('/get-tipo-menu',
    [\App\Http\Controllers\TipoMenuController::class, 'getTipoMenu']);
Route::post('/save-tipo-menu',
    [\App\Http\Controllers\TipoMenuController::class, 'storeTipoMenu']);
Route::put('/update-tipo-menu/{id}',
    [\App\Http\Controllers\TipoMenuController::class, 'updateTipoMenu']);
Route::put('/delete-tipo-menu/{id}',
    [\App\Http\Controllers\TipoMenuController::class, 'deleteTipoMenu']);
