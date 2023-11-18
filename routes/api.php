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
Route::post('/test-guardar-xxx', [RolesController::class, 'guardarTest']);
Route::get('/test-obtener-roles', [RolesController::class, 'show']);
Route::post('/test-guardar-rol', [RolesController::class, 'store']);
Route::put('/test-actualizar-rol', [RolesController::class, 'update']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/** TABLA MENU */
Route::get('/get-menus',
    [\App\Http\Controllers\MenuController::class, 'getMenus']);
Route::post('/save-menu',
    [\App\Http\Controllers\MenuController::class, 'storeMenu']);
Route::put('/update-menu/{id}',
    [\App\Http\Controllers\MenuController::class, 'updateMenu']);
Route::put('/delete-menu/{id}',
    [\App\Http\Controllers\MenuController::class, 'deleteMenu']);

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

/** TABLA RESERVACIONES */
Route::get('/get-reservaciones',
    [\App\Http\Controllers\ReservationController::class, 'getReservations']);
Route::post('/save-reservacion',
    [\App\Http\Controllers\ReservationController::class, 'storeReservation']);
Route::put('/update-reservacion/{id}',
    [\App\Http\Controllers\ReservationController::class, 'updateReservation']);
Route::put('/delete-reservacion/{id}',
    [\App\Http\Controllers\ReservationController::class, 'deleteReservation']);

/** SECURITY WEB */
Route::get('/client/login-user',
    [\App\Http\Controllers\SecurityClientController::class, 'loginUserWeb']);

Route::post('/client/register-user',
    [\App\Http\Controllers\SecurityClientController::class, 'registerUserClient']);

/** SECURITY MOBILE */
Route::post('/mobile/register-user',
    [\App\Http\Controllers\SecurityMobileController::class, 'registerUserMobile']);

Route::post('/mobile/login-user',
    [\App\Http\Controllers\SecurityMobileController::class, 'loginUserMobile']);

Route::get('/mobile/get-personal-information/{id}',
    [\App\Http\Controllers\SecurityMobileController::class, 'getPersonalInformation']);

Route::put('/mobile/update-personal-information',
    [\App\Http\Controllers\SecurityMobileController::class, 'updatePersonalInformation']);
