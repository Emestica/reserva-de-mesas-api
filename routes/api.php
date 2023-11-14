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

/** ESTAS RUTAS SON SOLO PARA PRUEBAS CON PATRON REPOSITORIO */
Route::get('/test-obtener-roles', [RolesController::class, 'show']);
Route::post('/test-guardar-rol', [RolesController::class, 'store']);
Route::put('/test-actualizar-rol', [RolesController::class, 'update']);

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

/** TABLA PAISES */
Route::get('/get-paises',
    [\App\Http\Controllers\PaisesController::class, 'getPaises']);
Route::post('/save-pais',
    [\App\Http\Controllers\PaisesController::class, 'storePais']);
Route::put('/update-pais/{id}',
    [\App\Http\Controllers\PaisesController::class, 'updatePais']);
Route::put('/delete-pais/{id}',
    [\App\Http\Controllers\PaisesController::class, 'deletePais']);

/** TABLA DEPARTAMENTOS */
Route::get('/get-departamentos',
    [\App\Http\Controllers\DepartamentosController::class, 'getDepartamentos']);
Route::post('/save-departamento',
    [\App\Http\Controllers\DepartamentosController::class, 'storeDepartamento']);
Route::put('/update-departamento/{id}',
    [\App\Http\Controllers\DepartamentosController::class, 'updateDepartamento']);
Route::put('/delete-departamento/{id}',
    [\App\Http\Controllers\DepartamentosController::class, 'deleteDepartamento']);

/** TABLA MUNICIPIOS */
Route::get('/get-municipios',
    [\App\Http\Controllers\MunicipiosController::class, 'getMunicipios']);
Route::post('/save-municipio',
    [\App\Http\Controllers\MunicipiosController::class, 'storeMunicipios']);
Route::put('/update-municipio/{id}',
    [\App\Http\Controllers\MunicipiosController::class, 'updateMunicipios']);
Route::put('/delete-municipio/{id}',
    [\App\Http\Controllers\MunicipiosController::class, 'deleteMunicipio']);

/** TABLA ROLES */
Route::get('/get-roles',
    [\App\Http\Controllers\ProfileController::class, 'getRoles']);
Route::post('/save-rol',
    [\App\Http\Controllers\ProfileController::class, 'storeRol']);
Route::put('/update-rol/{id}',
    [\App\Http\Controllers\ProfileController::class, 'updateRol']);
Route::put('/delete-rol/{id}',
    [\App\Http\Controllers\ProfileController::class, 'deleteRol']);

/** TABLA TIPO PERSONAS */
Route::get('/get-tipo-personas',
    [\App\Http\Controllers\TipoPersonasController::class, 'getTipoPersonas']);
Route::post('/save-tipo-persona',
    [\App\Http\Controllers\TipoPersonasController::class, 'storeTipoPersona']);
Route::put('/update-tipo-persona/{id}',
    [\App\Http\Controllers\TipoPersonasController::class, 'updateTipoPersona']);
Route::put('/delete-tipo-persona/{id}',
    [\App\Http\Controllers\TipoPersonasController::class, 'deleteTipoPersona']);

/** TABLA PERSONAS */
Route::get('/get-personas',
    [\App\Http\Controllers\PersonasController::class, 'getPersons']);
Route::post('/save-persona',
    [\App\Http\Controllers\PersonasController::class, 'storePerson']);
Route::put('/update-persona/{id}',
    [\App\Http\Controllers\PersonasController::class, 'updatePerson']);
Route::put('/delete-persona/{id}',
    [\App\Http\Controllers\PersonasController::class, 'deletePerson']);

/** TABLA RESTAURANTES */
Route::get('/get-restaurantes',
    [\App\Http\Controllers\RestaurantController::class, 'getRestaurants']);
Route::post('/save-restaurante',
    [\App\Http\Controllers\RestaurantController::class, 'storeRestaurant']);
Route::put('/update-restaurante/{id}',
    [\App\Http\Controllers\RestaurantController::class, 'updateRestaurant']);
Route::put('/delete-restaurante/{id}',
    [\App\Http\Controllers\RestaurantController::class, 'deleteRestaurant']);

/** TABLA MESAS */
Route::get('/get-mesas',
    [\App\Http\Controllers\TableController::class, 'getTables']);
Route::post('/save-mesa',
    [\App\Http\Controllers\TableController::class, 'storeTable']);
Route::put('/update-mesa/{id}',
    [\App\Http\Controllers\TableController::class, 'updateTable']);
Route::put('/delete-mesa/{id}',
    [\App\Http\Controllers\TableController::class, 'deleteTable']);

/** TEST */
Route::post('/client/register-user',
    [\App\Http\Controllers\SecurityClientController::class, 'registerUserClient']);

Route::post('/mobile/register-user',
    [\App\Http\Controllers\SecurityMobileController::class, 'registerUserMobile']);
