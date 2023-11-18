<?php

namespace App\Http\Controllers;

use App\Models\Restaurantes;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    private string $clazz = RestaurantController::class;

    public function getRestaurants(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->getRestaurants() => init');

        $option = $request->opcion;
        $state = $request->estado;
        $idRestaurant = $request->idrestaurante;
        $idMunicipio = $request->idMunicipio;

        Log::info($this->clazz.'->getRestaurants => option:       '.$option);
        Log::info($this->clazz.'->getRestaurants => state:        '.$state);
        Log::info($this->clazz.'->getRestaurants => idRestaurant: '.$idRestaurant);
        Log::info($this->clazz.'->getRestaurants => idRestaurant: '.$idMunicipio);

        try{
            switch ($option)
            {
                case 1:
                    /** TRAE UN LISTADO DE RESTAURANTE POR ESTADO */
                    Log::info($this->clazz.'->getRestaurants() => MSG: TRAE UN LISTADO DE RESTAURANTE POR ESTADO: '.$state);

                    $result = Restaurantes::query(
                    )->where(
                        'estado',
                        '=',
                        $state
                    )->get();

                    if($result->count() > 0){
                        return response()->json([
                            'title' => 'TRAE UN LISTADO DE RESTAURANTE POR ESTADO',
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN LISTADO DE RESTAURANTE POR ESTADO',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                case 2:
                    /** TRAE UN OBJETO DE RESTAURANTE POR ID */
                    Log::info($this->clazz.'->getRestaurants() => MSG: TRAE UN OBJETO DE RESTAURANTE POR ID: '.$idRestaurant);

                    $result = Restaurantes::query()->where(
                        'id_restaurante',
                        '=',
                        $idRestaurant
                    )->first();

                    if($result){
                        return response()->json([
                            'title' => 'TRAE UN OBJETO DE RESTAURANTE POR ID',
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN OBJETO DE RESTAURANTE POR ID',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                case 3:
                    /** TRAE UN LISTADO DE RESTAURANTES POR ID MUNICIPIO Y ESTADO */
                    Log::info($this->clazz.'->getRestaurants() => MSG: TRAE UN LISTADO DE RESTAURANTES POR ID MUNICIPIO Y ESTADO');
                    $result = Restaurantes::query(
                    )->join(
                        'municipios',
                        'restaurantes.id_municipio',
                        '=',
                        'municipios.id_municipio'
                    )->select(
                        'restaurantes.*',
                        'municipios.municipio'
                    )->where(
                        'restaurantes.id_municipio',
                        '=',
                        $idMunicipio
                    )->where(
                        'restaurantes.estado',
                        '=',
                        $state
                    )->get();

                    if($result->count() > 0){
                        return response()->json([
                            'title' => 'TRAE UN LISTADO DE RESTAURANTES POR ID MUNICIPIO Y ESTADO',
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN LISTADO DE RESTAURANTES POR ID MUNICIPIO Y ESTADO',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                case 4:
                    /** TRAE UN LISTADO RESTAURANTES RELACION CON EL MUNICIPIO DONDE EL ESTADO ES ACTIVO E INACTIVO */
                    Log::info($this->clazz.'->getRestaurants() => MSG: TRAE UN LISTADO RESTAURANTES RELACION CON EL MUNICIPIO DONDE EL ESTADO ES ACTIVO E INACTIVO');
                    $result = Restaurantes::query(
                    )->join(
                        'municipios',
                        'restaurantes.id_municipio',
                        '=',
                        'municipios.id_municipio'
                    )->select(
                        'restaurantes.*',
                        'municipios.municipio'
                    )->whereIn(
                        'restaurantes.estado',
                        array(
                            Constantes::ESTADO_ACTIVO,
                            Constantes::ESTADO_INACTIVO
                        )
                    )->get();

                    if($result->count() > 0){
                        return response()->json([
                            'title' => 'TRAE UN LISTADO RESTAURANTES RELACION CON EL MUNICIPIO DONDE EL ESTADO ES ACTIVO E INACTIVO',
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN LISTADO RESTAURANTES RELACION CON EL MUNICIPIO DONDE EL ESTADO ES ACTIVO E INACTIVO',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                default:
                    /** TRAE UN LISTADO COMPLETO DE RESTAURANTES SIN CONDICIONES */
                    Log::info($this->clazz.'->getRestaurants() => MSG: TRAE UN LISTADO COMPLETO DE PERSONAS SIN CONDICIONES');

                    $result = Restaurantes::all();

                    if($result->count() > 0){
                        return response()->json([
                            'title' => 'TRAE UN LISTADO COMPLETO DE RESTAURANTES SIN CONDICIONES',
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN LISTADO COMPLETO DE RESTAURANTES SIN CONDICIONES',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->getRestaurants() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storeRestaurant(Request $request) : JsonResponse
    {
        Log::info($this->clazz.'->storeRestaurant() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_municipio' => 'required',
                'nombre_legal' => 'required',
                'restaurante' => 'required',
                'descripcion' => 'required',
                'direccion' => 'required',
                'telefono' => 'required',
                'celular' => 'required',
                'correo' => 'required',
                'pagina_web' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = Restaurantes::create($request->all());

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Guardado Correctamente!!!',
                    'result' => $result
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->storeRestaurant() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function updateRestaurant(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'->updateRestaurant() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_municipio' => 'required',
                'nombre_legal' => 'required',
                'restaurante' => 'required',
                'descripcion' => 'required',
                'direccion' => 'required',
                'telefono' => 'required',
                'celular' => 'required',
                'correo' => 'required',
                'pagina_web' => 'required',
                'estado' => 'required',
                'usuario_modificacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $restaurant = Restaurantes::find($id);

                if($restaurant){

                    $restaurant->id_municipio = $request->id_municipio;
                    $restaurant->nombre_legal = $request->nombre_legal;
                    $restaurant->restaurante = $request->restaurante;
                    $restaurant->descripcion = $request->descripcion;
                    $restaurant->direccion = $request->direccion;
                    $restaurant->telefono = $request->telefono;
                    $restaurant->celular = $request->celular;
                    $restaurant->correo = $request->correo;
                    $restaurant->pagina_web = $request->pagina_web;

                    $restaurant->estado = $request->estado;
                    $restaurant->usuario_modificacion = $request->usuario_modificacion;

                    $result = $restaurant->save();

                    return response()->json([
                        'success' => true,
                        'data' => 'Registro Actualizado Correctamente!!!',
                        'result' => $result
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'data' => 'Registro #: '.$id.' No Encontrado!!!'
                    ]);
                }
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->updateRestaurant() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deleteRestaurant($id) : JsonResponse
    {
        Log::info($this->clazz.'->deleteRestaurant() => init');
        try {
            $restaurant = Restaurantes::find($id);

            if($restaurant){
                $restaurant->estado = Constantes::ESTADO_ELIMINADO;
                $result = $restaurant->save();

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Eliminado Correctamente!!!',
                    'result' => $result
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'data' => 'Registro #: '.$id.' No Encontrado!!!'
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->deleteRestaurant() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
