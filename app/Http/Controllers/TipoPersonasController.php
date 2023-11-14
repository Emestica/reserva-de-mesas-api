<?php

namespace App\Http\Controllers;

use App\Models\TipoPersonas;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TipoPersonasController extends Controller
{
    private string $clazz = TipoPersonasController::class;

    public function getTipoPersonas(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->getTipoPersonas() => init');

        $option = $request->opcion;
        $estado = $request->estado;
        $idtipopersona = $request->idtipopersona;

        Log::info($this->clazz.'->getTipoPersonas => option:   '.$option);
        Log::info($this->clazz.'->getTipoPersonas => estado:   '.$estado);
        Log::info($this->clazz.'->getTipoPersonas => idtipopersona:    '.$idtipopersona);

        try{
            switch ($option)
            {
                case 1:
                    /** TRAE UN LISTADO DE TIPO PERSONAS POR ESTADO */
                    Log::info($this->clazz.'->getTipoPersonas() => MSG: TRAE UN LISTADO DE TIPO PERSONAS POR ESTADO: '.$estado);

                    $result = TipoPersonas::query(
                    )->where(
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
                    /** TRAE UN OBJETO DE TIPO PERSONA POR ID */
                    Log::info($this->clazz.'->getTipoPersonas() => MSG: TRAE UN OBJETO DE TIPO PERSONA POR ID: '.$idtipopersona);

                    $result = TipoPersonas::query()->where(
                        'id_tipo_persona',
                        '=',
                        $idtipopersona
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
                    /** TRAE UN LISTADO COMPLETO DE ROLES SIN CONDICIONES */
                    Log::info($this->clazz.'->getTipoPersonas() => MSG: TRAE UN LISTADO COMPLETO DE ROLES SIN CONDICIONES');

                    $result = TipoPersonas::all();

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
            Log::error($this->clazz.'->getTipoPersonas() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storeTipoPersona(Request $request) : JsonResponse
    {
        Log::info($this->clazz.'->storeTipoPersona() => init');
        try {
            $validation = Validator::make($request->all(), [
                'tipo_persona' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = TipoPersonas::create($request->all());

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Guardado Correctamente!!!',
                    'result' => $result
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->storeTipoPersona() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function updateTipoPersona(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'->updateTipoPersona() => init');
        try {
            $validation = Validator::make($request->all(), [
                'tipo_persona' => 'required',
                'estado' => 'required',
                'usuario_modificacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $tipoPersona = TipoPersonas::find($id);

                if($tipoPersona){

                    $tipoPersona->tipo_persona = $request->tipo_persona;
                    $tipoPersona->estado = $request->estado;
                    $tipoPersona->usuario_modificacion = $request->usuario_modificacion;

                    $result = $tipoPersona->save();

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
            Log::error($this->clazz.'->updateTipoPersona() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deleteTipoPersona($id) : JsonResponse
    {
        Log::info($this->clazz.'->deleteTipoPersona() => init');
        try {
            $tipoPersona = TipoPersonas::find($id);

            if($tipoPersona){
                $tipoPersona->estado = Constantes::ESTADO_ELIMINADO;
                $result = $tipoPersona->save();

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
            Log::error($this->clazz.'->deleteTipoPersona() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
