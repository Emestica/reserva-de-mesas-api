<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    private string $clazz = MunicipiosController::class;

    public function getRoles(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->getRoles() => init');

        $option = $request->opcion;
        $estado = $request->estado;
        $idrol = $request->idrol;

        Log::info($this->clazz.'->getRoles => option:   '.$option);
        Log::info($this->clazz.'->getRoles => estado:   '.$estado);
        Log::info($this->clazz.'->getRoles => idrol:    '.$idrol);

        try{
            switch ($option)
            {
                case 1:
                    /** TRAE UN LISTADO DE ROLES POR ESTADO */
                    Log::info($this->clazz.'->getRoles() => MSG: TRAE UN LISTADO DE ROLES POR ESTADO: '.$estado);

                    $result = Roles::query(
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
                    /** TRAE UN OBJETO DE ROL POR ID */
                    Log::info($this->clazz.'->getRoles() => MSG: TRAE UN OBJETO DE ROL POR ID: '.$idrol);

                    $result = Roles::query()->where(
                        'id_rol',
                        '=',
                        $idrol
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
                    Log::info($this->clazz.'->getRoles() => MSG: TRAE UN LISTADO COMPLETO DE ROLES SIN CONDICIONES');

                    $result = Roles::all();

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
            Log::error($this->clazz.'->getRoles() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storeRol(Request $request) : JsonResponse
    {
        Log::info($this->clazz.'->storeRol() => init');
        try {
            $validation = Validator::make($request->all(), [
                'rol' => 'required',
                'descripcion' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = Roles::create($request->all());

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Guardado Correctamente!!!',
                    'result' => $result
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->storeRol() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function updateRol(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'->updateRol() => init');
        try {
            $validation = Validator::make($request->all(), [
                'rol' => 'required',
                'descripcion' => 'required',
                'estado' => 'required',
                'usuario_modificacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $rol = Roles::find($id);

                if($rol){

                    $rol->rol = $request->rol;
                    $rol->descripcion = $request->descripcion;
                    $rol->estado = $request->estado;
                    $rol->usuario_modificacion = $request->usuario_modificacion;

                    $result = $rol->save();

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
            Log::error($this->clazz.'->updateRol() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deleteRol($id) : JsonResponse
    {
        Log::info($this->clazz.'->deleteRol() => init');
        try {
            $rol = Roles::find($id);

            if($rol){
                $rol->estado = Constantes::ESTADO_ELIMINADO;
                $result = $rol->save();

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
            Log::error($this->clazz.'->deleteRol() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
