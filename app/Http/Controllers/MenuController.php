<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    private string $clazz = MenuController::class;

    public function getMenu(Request $request): JsonResponse
    {
        Log::info($this->clazz.'getMenu() => init');

        $option = $request->opcion;
        $id_menu = $request->idmenu;
        $id_restaurante = $request->idrestaurante;
        $estado = $request->estado;

        Log::info($this->clazz.'getMenu() => opcion:         '.$option);
        Log::info($this->clazz.'getMenu() => menu:           '.$id_menu);
        Log::info($this->clazz.'getMenu() => restaurante:    '.$id_restaurante);
        Log::info($this->clazz.'getMenu() => estado:         '.$estado);

        try {
            switch ($option)
            {
                case 1:
                    /** TRAE LOS MENU POR ID RESTAURANTE Y ESTADO ACTIVO */
                    Log::info($this->clazz.'getMenu() => msg: MENU POR ID RESTAURANTE Y ESTADO: '.$estado);

                    $result = Menu::select(
                        'menu.id_menu',
                        'menu.nombre',
                        'menu.descripcion',
                        'menu.precio',
                        'menu.descuento',
                        'menu.disponible',
                        'menu.estado',
                        'menu.id_restaurante',
                        'restaurantes.nombre_legal',
                        'restaurantes.restaurante',
                        'menu.id_clasificacion',
                        'clasificacion.clasificacion',
                        'menu.id_tipo_menu',
                        'tipo_menu.tipo_menu as nombre_tipo_menu',
                    )->join(
                        'restaurantes',
                        'restaurantes.id_restaurante',
                        '=',
                        'menu.id_restaurante'
                    )->join(
                        'clasificacion',
                        'clasificacion.id_clasificacion',
                        '=',
                        'menu.id_clasificacion'
                    )->join(
                        'tipo_menu',
                        'tipo_menu.id_tipo_menu',
                        '=',
                        'menu.id_tipo_menu'
                    )->where(
                        'menu.estado',
                        '=',
                        $estado
                    )->where(
                        'menu.id_restaurante',
                        '=',
                        $id_restaurante
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
                    /** TRAE UN MENU POR ID MENU Y ESTADO */
                    Log::info($this->clazz.'getMenu() => msg: TRAE UN MENU POR ID MENU Y ESTADO: '.$estado);

                    $result = Menu::select(
                        'menu.id_menu',
                        'menu.nombre',
                        'menu.descripcion',
                        'menu.precio',
                        'menu.descuento',
                        'menu.disponible',
                        'menu.estado',
                        'menu.id_restaurante',
                        'restaurantes.nombre_legal',
                        'restaurantes.restaurante',
                        'menu.id_clasificacion',
                        'clasificacion.clasificacion',
                        'menu.id_tipo_menu',
                        'tipo_menu.tipo_menu as nombre_tipo_menu',
                    )->join(
                        'restaurantes',
                        'restaurantes.id_restaurante',
                        '=',
                        'menu.id_restaurante'
                    )->join(
                        'clasificacion',
                        'clasificacion.id_clasificacion',
                        '=',
                        'menu.id_clasificacion'
                    )->join(
                        'tipo_menu',
                        'tipo_menu.id_tipo_menu',
                        '=',
                        'menu.id_tipo_menu'
                    )->where(
                        'menu.estado',
                        '=',
                        $estado
                    )->where(
                        'menu.id_menu',
                        '=',
                        $id_menu
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
                    /** TRAE LOS MENUS SOLO Y UNICAMENTE POR ID RESTAURANTE */
                    Log::info($this->clazz.'getMenu() => msg: TRAE LOS MENUS SOLO Y UNICAMENTE POR ID RESTAURANTE');

                    $result = Menu::select(
                        'menu.id_menu',
                        'menu.nombre',
                        'menu.descripcion',
                        'menu.precio',
                        'menu.descuento',
                        'menu.disponible',
                        'menu.estado',
                        'menu.id_restaurante',
                        'restaurantes.nombre_legal',
                        'restaurantes.restaurante',
                        'menu.id_clasificacion',
                        'clasificacion.clasificacion',
                        'menu.id_tipo_menu',
                        'tipo_menu.tipo_menu as nombre_tipo_menu',
                    )->join(
                        'restaurantes',
                        'restaurantes.id_restaurante',
                        '=',
                        'menu.id_restaurante'
                    )->join(
                        'clasificacion',
                        'clasificacion.id_clasificacion',
                        '=',
                        'menu.id_clasificacion'
                    )->join(
                        'tipo_menu',
                        'tipo_menu.id_tipo_menu',
                        '=',
                        'menu.id_tipo_menu'
                    )->where(
                        'menu.id_restaurante',
                        '=',
                        $id_restaurante
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
            Log::error($this->clazz.'getMenu() => error: '.$throwable->getMessage());
            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
