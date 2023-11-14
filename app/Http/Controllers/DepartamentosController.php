<?php

namespace App\Http\Controllers;

use App\Models\Departamentos;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DepartamentosController extends Controller
{
    private string $clazz = DepartamentosController::class;

    public function getDepartamentos(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->getDepartamentos() => init');

        $option = $request->opcion;
        $estado = $request->estado;
        $iddepartamento = $request->iddepartamento;
        $idpais = $request->idpais;

        Log::info($this->clazz.'->getDepartamentos() => option: '.$option);
        Log::info($this->clazz.'->getDepartamentos() => estado: '.$estado);
        Log::info($this->clazz.'->getDepartamentos() => iddepartamento: '.$iddepartamento);
        Log::info($this->clazz.'->getDepartamentos() => idpais: '.$idpais);

        try{
            switch ($option)
            {
                case 1:
                    /** TRAE UN LISTADO DE DEPARTAMENTOS POR ID PAIS Y ESTADO */
                    Log::info($this->clazz.'->getDepartamentos() => MSG: TRAE UN LISTADO DE DEPARTAMENTOS POR ID PAIS Y ESTADO');

                    $result = Departamentos::query(
                    )->join(
                        'paises',
                        'departamentos.id_pais',
                        '=',
                        'paises.id_pais'
                    )->select(
                        'departamentos.*',
                        'paises.pais'
                    )->where(
                        'departamentos.id_pais',
                        '=',
                        $idpais
                    )->where(
                        'departamentos.estado',
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
                    /** TRAE UN OBJETO DE DEPARTAMENTO POR ID */
                    Log::info($this->clazz.'->getDepartamentos() => MSG: TRAE UN OBJETO DE DEPARTAMENTO POR ID: '.$iddepartamento);

                    $result = Departamentos::query(
                    )->where(
                        'id_departamento',
                        '=',
                        $iddepartamento
                    )->first();

                    if($result){
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
                default:
                    /** TRAE UN LISTADO COMPLETO DE DEPARTAMENTOS SIN CONDICIONES */
                    Log::info($this->clazz.'->getDepartamentos() => MSG: TRAE UN LISTADO COMPLETO DE DEPARTAMENTOS SIN CONDICIONES');

                    $result = Departamentos::all();

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
            }

        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->getDepartamentos() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storeDepartamento(Request $request) : JsonResponse
    {
        Log::info($this->clazz.'->storeDepartamento() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_pais' => 'required',
                'departamento' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = Departamentos::create($request->all());

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

    public function updateDepartamento(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'->updateDepartamento() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_pais' => 'required',
                'departamento' => 'required',
                'estado' => 'required',
                'usuario_modificacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $departamento = Departamentos::find($id);

                if($departamento){

                    $departamento->id_pais = $request->id_pais;
                    $departamento->departamento = $request->departamento;
                    $departamento->estado = $request->estado;
                    $departamento->usuario_modificacion = $request->usuario_modificacion;

                    $result = $departamento->save();

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
            Log::error($this->clazz.'->updateDepartamento() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deleteDepartamento($id) : JsonResponse
    {
        Log::info($this->clazz.'->deleteDepartamento() => init');
        try {
            $departamento = Departamentos::find($id);

            if($departamento){
                $departamento->estado = Constantes::ESTADO_ELIMINADO;
                $result = $departamento->save();

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
            Log::error($this->clazz.'->deleteDepartamento() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
