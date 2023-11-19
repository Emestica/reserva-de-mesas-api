<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClasificacionController extends Controller
{
    private string $clazz = ClasificacionController::class;

    public function getClasificaciones(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->getClasificaciones() => init');

        $option = $request->opcion;
        $estado = $request->estado;
        $idclasificacion = $request->idclasificacion;

        Log::info($this->clazz.'->getClasificaciones() => option: '.$option);
        Log::info($this->clazz.'->getClasificaciones() => estado: '.$estado);
        Log::info($this->clazz.'->getClasificaciones() => idclasificacion: '.$idclasificacion);

        try{
            switch ($option)
            {
                case 1:
                    /** TRAE UN LISTADO DE CLASIFICACIONES POR ESTADO */
                    Log::info($this->clazz.'->getClasificaciones() => msg: TRAE UN LISTADO DE CLASIFICACIONES POR ESTADO');

                    $result = Clasificacion::query()->where(
                        'estado',
                        '=',
                        $estado
                    )->get();

                    if($result->count() > 0){
                        return response()->json([
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                case 2:
                    /** TRAE UN OBJETO DE CLASIFICACION POR ID */
                    Log::info($this->clazz.'->getClasificaciones() => msg: TRAE UN OBJETO DE CLASIFICACION POR ID');

                    $result = Clasificacion::query()->where(
                        'id_clasificacion',
                        '=',
                        $idclasificacion
                    )->first();

                    if($result){
                        return response()->json([
                            'code' => 200,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'code' => 200,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                case 3:
                    /** TRAE UN OBJETO DE CLASIFICACION POR ID */
                    Log::info($this->clazz.'->getClasificaciones() => msg: TRAE UN OBJETO DE CLASIFICACION POR ID');

                    $result = Clasificacion::query(
                    )->whereIn(
                        'clasificacion.estado',
                        array(
                            Constantes::ESTADO_ACTIVO,
                            Constantes::ESTADO_INACTIVO
                        )
                    )->get();

                    if($result){
                        return response()->json([
                            'code' => 200,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'code' => 200,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                default:
                    /** TRAE UN LISTADO COMPLETO DE CLASIFICACIONES SIN CONDICIONES */
                    Log::info($this->clazz.'->getClasificaciones() => msg: TRAE UN LISTADO COMPLETO DE CLASIFICACIONES SIN CONDICIONES');

                    $result = Clasificacion::all();

                    if($result->count() > 0){
                        return response()->json([
                            'code' => 200,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'code' => 200,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
            }

        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->getClasificaciones() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storeClasificacion(Request $request) : JsonResponse
    {
        Log::info($this->clazz.'->storeClasificacion() => init');
        try {
            $validation = Validator::make($request->all(), [
                'clasificacion' => 'required',
                'descripcion' => 'required',
                //'icon' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = Clasificacion::create($request->all());

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Guardado Correctamente!!!',
                    'result' => $result
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->storeClasificacion() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function updateClasificacion(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'->updateClasificacion() => init');
        try {
            $validation = Validator::make($request->all(), [
                'clasificacion' => 'required',
                'descripcion' => 'required',
                //'icon' => 'required',
                'estado' => 'required',
                'usuario_modificacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $clasificacion = Clasificacion::find($id);

                if($clasificacion){

                    $clasificacion->clasificacion = $request->clasificacion;
                    $clasificacion->descripcion = $request->descripcion;
                    //$clasificacion->icon = $request->icon;
                    $clasificacion->estado = $request->estado;
                    $clasificacion->usuario_modificacion = $request->usuario_modificacion;

                    $result = $clasificacion->save();

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
            Log::error($this->clazz.'->updateClasificacion() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deleteClasificacion($id) : JsonResponse
    {
        Log::info($this->clazz.'->deleteClasificacion() => init');
        try {
            $clasificacion = Clasificacion::find($id);

            if($clasificacion){
                $clasificacion->estado = Constantes::ESTADO_ELIMINADO;
                $result = $clasificacion->save();

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
            Log::error($this->clazz.'->deleteClasificacion() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
