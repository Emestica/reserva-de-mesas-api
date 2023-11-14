<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    private string $clazz = TableController::class;

    public function getTables(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->getTables() => init');

        $option = $request->opcion;
        $state = $request->estado;
        $idTable = $request->idmesa;
        $idRestaurant = $request->idrestaurante;

        Log::info($this->clazz.'->getTables => option:       '.$option);
        Log::info($this->clazz.'->getTables => state:        '.$state);
        Log::info($this->clazz.'->getTables => idTable:      '.$idTable);
        Log::info($this->clazz.'->getTables => idRestaurant: '.$idRestaurant);

        try{
            switch ($option)
            {
                case 1:
                    /** TRAE UN LISTADO DE MESAS POR ID RESTAURANTE Y ESTADO */
                    Log::info($this->clazz.'->getTables() => MSG: TRAE UN LISTADO DE MESAS POR ID RESTAURANTE Y ESTADO: '.$state);

                    $result = Table::query(
                    )->join(
                        'restaurantes',
                        'mesas.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->select(
                        'mesas.*',
                        'restaurantes.restaurante',
                        'restaurantes.nombre_legal',
                    )->where(
                        'mesas.id_restaurante',
                        '=',
                        $idRestaurant
                    )->where(
                        'mesas.estado',
                        '=',
                        $state
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
                    /** TRAE UN OBJETO DE MESA POR ID */
                    Log::info($this->clazz.'->getTables() => MSG: TRAE UN OBJETO DE RESTAURANTE POR ID: '.$idTable);

                    $result = Table::query(
                    )->join(
                        'restaurantes',
                        'mesas.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->select(
                        'mesas.*',
                        'restaurantes.restaurante',
                        'restaurantes.nombre_legal',
                    )->where(
                        'id_mesa',
                        '=',
                        $idTable
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
                    /** TRAE UN LISTADO DE MESAS POR ID RESTAURANTE Y ESTADO ACTIVO Y INACTIVO */
                    Log::info($this->clazz.'->getTables() => MSG: TRAE UN LISTADO DE MESAS POR ID RESTAURANTE Y ESTADO ACTIVO Y INACTIVO');
                    $result = Table::query(
                    )->join(
                        'restaurantes',
                        'mesas.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->select(
                        'mesas.*',
                        'restaurantes.restaurante',
                        'restaurantes.nombre_legal',
                    )->where(
                        'mesas.id_restaurante',
                        '=',
                        $idRestaurant
                    )->whereIn(
                        'mesas.estado',
                        array(
                            Constantes::ESTADO_ACTIVO,
                            Constantes::ESTADO_INACTIVO
                        )
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
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->getTables() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storeTable(Request $request) : JsonResponse
    {
        Log::info($this->clazz.'->storeTable() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_restaurante' => 'required',
                'mesa' => 'required',
                'descripcion' => 'required',
                'numero' => 'required',
                'capacidad' => 'required',
                'disponible' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = Table::create($request->all());

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Guardado Correctamente!!!',
                    'result' => $result
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->storeTable() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function updateTable(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'->updateTable() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_restaurante' => 'required',
                'mesa' => 'required',
                'descripcion' => 'required',
                'numero' => 'required',
                'capacidad' => 'required',
                'disponible' => 'required',
                'estado' => 'required',
                'usuario_modificacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $table = Table::find($id);

                if($table){

                    $table->id_restaurante = $request->id_restaurante;
                    $table->mesa = $request->mesa;
                    $table->descripcion = $request->descripcion;
                    $table->numero = $request->numero;
                    $table->capacidad = $request->capacidad;
                    $table->disponible = $request->disponible;

                    $table->estado = $request->estado;
                    $table->usuario_modificacion = $request->usuario_modificacion;

                    $result = $table->save();

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
            Log::error($this->clazz.'->updateTable() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deleteTable($id) : JsonResponse
    {
        Log::info($this->clazz.'->deleteTable() => init');
        try {
            $table = Table::find($id);

            if($table){
                $table->estado = Constantes::ESTADO_ELIMINADO;
                $result = $table->save();

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
            Log::error($this->clazz.'->deleteTable() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
