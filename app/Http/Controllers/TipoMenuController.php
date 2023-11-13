<?php

namespace App\Http\Controllers;

use App\Models\TipoMenu;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TipoMenuController extends Controller
{
    private string $clazz = ClasificacionController::class;

    public function getTipoMenu(Request $request):JsonResponse
    {
        Log::info($this->clazz.'getTipoMenu() => init');

        $option = $request->opcion;
        $estado = $request->estado;
        $idtipomenu = $request->idtipomenu;

        Log::info($this->clazz.'getTipoMenu() => option: '.$option);
        Log::info($this->clazz.'getTipoMenu() => estado: '.$estado);
        Log::info($this->clazz.'getTipoMenu() => idtipomenu: '.$idtipomenu);

        try{
            switch ($option)
            {
                case 1:
                    /** TRAE UN LISTADO DE TIPOS DE MENU POR ESTADO */
                    Log::info($this->clazz.'getTipoMenu() => msg: TRAE UN LISTADO DE TIPOS DE MENU POR ESTADO');

                    $result = TipoMenu::query()->where(
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
                    /** TRAE UN OBJETO DE TIPO DE MENU POR ID */
                    Log::info($this->clazz.'getTipoMenu() => msg: TRAE UN OBJETO DE TIPO DE MENU POR ID');

                    $result = TipoMenu::query()->where(
                        'id_tipo_menu',
                        '=',
                        $idtipomenu
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
                    /** TRAE UN LISTADO COMPLETO DE TIPOS DE MENU SIN CONDICIONES */
                    Log::info($this->clazz.'getTipoMenu() => msg: TRAE UN LISTADO COMPLETO DE TIPOS DE MENU SIN CONDICIONES');

                    $result = TipoMenu::all();

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
            Log::error($this->clazz.'getTipoMenu() => init');

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storeTipoMenu(Request $request) : JsonResponse
    {
        Log::info($this->clazz.'storeTipoMenu() => init');
        try {
            $validation = Validator::make($request->all(), [
                'tipo_menu' => 'required',
                'descripcion' => 'required',
                'icon' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = TipoMenu::create($request->all());

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Guardado Correctamente!!!',
                    'result' => $result
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'storeTipoMenu() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function updateTipoMenu(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'updateTipoMenu() => init');
        try {
            $validation = Validator::make($request->all(), [
                'tipo_menu' => 'required',
                'descripcion' => 'required',
                'icon' => 'required',
                'estado' => 'required',
                'usuario_modificacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $objeto = TipoMenu::find($id);

                if($objeto){

                    $objeto->tipo_menu = $request->tipo_menu;
                    $objeto->descripcion = $request->descripcion;
                    $objeto->icon = $request->icon;
                    $objeto->estado = $request->estado;
                    $objeto->usuario_modificacion = $request->usuario_modificacion;

                    $result = $objeto->save();

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
            Log::error($this->clazz.'updateTipoMenu() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deleteTipoMenu($id) : JsonResponse
    {
        Log::info($this->clazz.'deleteTipoMenu() => init');
        try {
            $objeto = TipoMenu::find($id);

            if($objeto){
                $objeto->estado = Constantes::ESTADO_ELIMINADO;
                $result = $objeto->save();

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
            Log::error($this->clazz.'deleteTipoMenu() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
