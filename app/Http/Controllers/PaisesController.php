<?php

namespace App\Http\Controllers;

use App\Models\Paises;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaisesController extends Controller
{
    private string $clazz = PaisesController::class;

    public function getPaises(Request $request):JsonResponse
    {
        Log::info($this->clazz.'getPaises() => init');

        $option = $request->opcion;
        $estado = $request->estado;
        $idpais = $request->idpais;

        Log::info($this->clazz.'getPaises() => option: '.$option);
        Log::info($this->clazz.'getPaises() => estado: '.$estado);
        Log::info($this->clazz.'getPaises() => idpais: '.$idpais);

        try{
            switch ($option)
            {
                case 1:
                    /** TRAE UN LISTADO DE PAISES POR ESTADO */
                    Log::info($this->clazz.'getPaises() => msg: TRAE UN LISTADO DE TIPOS DE MENU POR ESTADO: '.$estado);

                    $result = Paises::query()->where(
                        'estado',
                        '=',
                        $estado
                    )->get();

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
                case 2:
                    /** TRAE UN OBJETO DE PAIS POR ID */
                    Log::info($this->clazz.'getPaises() => msg: TRAE UN OBJETO DE PAIS POR ID: '.$idpais);

                    $result = Paises::query()->where(
                        'id_pais',
                        '=',
                        $idpais
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
                default:
                    /** TRAE UN LISTADO COMPLETO DE PAISES SIN CONDICIONES */
                    Log::info($this->clazz.'getPaises() => msg: TRAE UN LISTADO COMPLETO DE PAISES SIN CONDICIONES');

                    $result = Paises::all();

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
            Log::error($this->clazz.'getPaises() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storePais(Request $request): JsonResponse
    {
        Log::info($this->clazz.'storePais() => init');
        try {
            $validation = Validator::make($request->all(), [
                'pais' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = Paises::create($request->all());

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Guardado Correctamente!!!',
                    'result' => $result
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'storePais() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function updatePais(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'updatePais() => init');
        try {
            $validation = Validator::make($request->all(), [
                'pais' => 'required',
                'estado' => 'required',
                'usuario_modificacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $pais = Paises::find($id);

                if($pais){

                    $pais->pais = $request->pais;
                    $pais->estado = $request->estado;
                    $pais->usuario_modificacion = $request->usuario_modificacion;

                    $result = $pais->save();

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
            Log::error($this->clazz.'updatePais() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deletePais($id) : JsonResponse
    {
        Log::info($this->clazz.'deletePais() => init');
        try {
            $pais = Paises::find($id);

            if($pais){
                $pais->estado = Constantes::ESTADO_ELIMINADO;
                $result = $pais->save();

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
            Log::error($this->clazz.'deleteClasificacion() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
