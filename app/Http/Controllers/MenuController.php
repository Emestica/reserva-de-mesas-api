<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    private string $clazz = MenuController::class;

    public function getMenus(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->getMenus() => init');

        $option = $request->opcion;
        $state = $request->estado;
        $idMenu = $request->idmenu;
        $idRestaurant = $request->idrestaurante;

        Log::info($this->clazz.'->getMenus => option:       '.$option);
        Log::info($this->clazz.'->getMenus => state:        '.$state);
        Log::info($this->clazz.'->getMenus => idMenu:       '.$idMenu);
        Log::info($this->clazz.'->getMenus => idRestaurant: '.$idRestaurant);

        try{
            switch ($option)
            {
                case 1:
                    /** TRAE UN LISTADO DE MENUS POR ID RESTAURANTE Y ESTADO */
                    Log::info($this->clazz.'->getMenus() => MSG: TRAE UN LISTADO DE MENUS POR ID RESTAURANTE Y ESTADO: '.$state);

                    $result = Menu::query(
                    )->join(
                        'restaurantes',
                        'menu.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->join(
                        'clasificacion',
                        'menu.id_clasificacion',
                        '=',
                        'clasificacion.id_clasificacion'
                    )->join(
                        'tipo_menu',
                        'menu.id_tipo_menu',
                        '=',
                        'tipo_menu.id_tipo_menu'
                    )->select(
                        'menu.*',
                        'restaurantes.restaurante',
                        'restaurantes.nombre_legal',
                        'clasificacion.clasificacion',
                        'clasificacion.descripcion',
                        'tipo_menu.tipo_menu',
                        'tipo_menu.descripcion',
                    )->where(
                        'menu.id_restaurante',
                        '=',
                        $idRestaurant
                    )->where(
                        'menu.estado',
                        '=',
                        $state
                    )->get();

                    if($result->count() > 0){
                        return response()->json([
                            'title' => 'TRAE UN LISTADO DE MENUS POR ID RESTAURANTE Y ESTADO',
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN LISTADO DE MENUS POR ID RESTAURANTE Y ESTADO',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                case 2:
                    /** TRAE UN OBJETO DE MENU POR ID */
                    Log::info($this->clazz.'->getMenus() => MSG: TRAE UN OBJETO DE MENU POR ID: '.$idMenu);

                    $result = Menu::query(
                    )->join(
                        'restaurantes',
                        'menu.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->join(
                        'clasificacion',
                        'menu.id_clasificacion',
                        '=',
                        'clasificacion.id_clasificacion'
                    )->join(
                        'tipo_menu',
                        'menu.id_tipo_menu',
                        '=',
                        'tipo_menu.id_tipo_menu'
                    )->select(
                        'menu.*',
                        'restaurantes.restaurante',
                        'restaurantes.nombre_legal',
                        'clasificacion.clasificacion',
                        'clasificacion.descripcion',
                        'tipo_menu.tipo_menu',
                        'tipo_menu.descripcion',
                    )->where(
                        'menu.id_menu',
                        '=',
                        $idMenu
                    )->first();

                    if($result){
                        return response()->json([
                            'title' => 'TRAE UN OBJETO DE MENU POR ID: '.$idMenu,
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN OBJETO DE MENU POR ID: '.$idMenu,
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                default:
                    /** TRAE UN LISTADO DE MESAS POR ID RESTAURANTE Y ESTADO ACTIVO Y INACTIVO */
                    Log::info($this->clazz.'->getMenus() => MSG: TRAE UN LISTADO DE MESAS POR ID RESTAURANTE Y ESTADO ACTIVO E INACTIVO');
                    $result = Menu::query(
                    )->join(
                        'restaurantes',
                        'menu.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->join(
                        'clasificacion',
                        'menu.id_clasificacion',
                        '=',
                        'clasificacion.id_clasificacion'
                    )->join(
                        'tipo_menu',
                        'menu.id_tipo_menu',
                        '=',
                        'tipo_menu.id_tipo_menu'
                    )->select(
                        'menu.*',
                        'restaurantes.restaurante',
                        'restaurantes.nombre_legal',
                        'clasificacion.clasificacion',
                        'clasificacion.descripcion',
                        'tipo_menu.tipo_menu',
                        'tipo_menu.descripcion',
                    )->where(
                        'menu.id_restaurante',
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
                            'title' => 'TRAE UN LISTADO DE MESAS POR ID RESTAURANTE Y ESTADO ACTIVO E INACTIVO',
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN LISTADO DE MESAS POR ID RESTAURANTE Y ESTADO ACTIVO E INACTIVO',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->getMenus() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storeMenu(Request $request) : JsonResponse
    {
        Log::info($this->clazz.'->storeMenu() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_restaurante' => 'required',
                'id_clasificacion' => 'required',
                'id_tipo_menu' => 'required',
                'nombre' => 'required',
                'informacion' => 'required',
                'precio' => 'required',
                'descuento' => 'required',
                'disponible' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = Menu::create($request->all());

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Guardado Correctamente!!!',
                    'result' => $result
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->storeMenu() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function updateMenu(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'->updateMenu() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_restaurante' => 'required',
                'id_clasificacion' => 'required',
                'id_tipo_menu' => 'required',
                'nombre' => 'required',
                'informacion' => 'required',
                'precio' => 'required',
                'descuento' => 'required',
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
                $menu = Menu::find($id);

                if($menu){

                    $menu->id_restaurante = $request->id_restaurante;
                    $menu->id_clasificacion = $request->id_clasificacion;
                    $menu->id_tipo_menu = $request->id_tipo_menu;
                    $menu->nombre = $request->nombre;
                    $menu->informacion = $request->informacion;
                    $menu->precio = $request->precio;
                    $menu->descuento = $request->descuento;
                    $menu->disponible = $request->disponible;

                    $menu->estado = $request->estado;
                    $menu->usuario_modificacion = $request->usuario_modificacion;

                    $result = $menu->save();

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
            Log::error($this->clazz.'->updateMenu() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deleteMenu($id) : JsonResponse
    {
        Log::info($this->clazz.'->deleteMenu() => init');
        try {
            $menu = Menu::find($id);

            if($menu){
                $menu->estado = Constantes::ESTADO_ELIMINADO;
                $result = $menu->save();

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
            Log::error($this->clazz.'->deleteMenu() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
