<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    private string $clazz = ReservationController::class;


    public function getReservations(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->getReservations() => init');

        $option = $request->opcion;
        $state = $request->estado;
        $idReservation = $request->idreservacion;
        $idUserPerson = $request->idmenu;
        $idRestaurant = $request->idrestaurante;

        Log::info($this->clazz.'->getReservations => option:       '.$option);
        Log::info($this->clazz.'->getReservations => state:        '.$state);
        Log::info($this->clazz.'->getReservations => idReservation:'.$idReservation);
        Log::info($this->clazz.'->getReservations => idUserPerson: '.$idUserPerson);
        Log::info($this->clazz.'->getReservations => idRestaurant: '.$idRestaurant);

        try{
            switch ($option) {
                case 1:
                    /** HISTORICO RESERVACIONES PARA USUARIOS */
                    Log::info($this->clazz . '->getReservations() => MSG: HISTORICO RESERVACIONES PARA USUARIOS');

                    $result = Reservation::query()->join(
                        'mesas',
                        'reservaciones.id_mesa',
                        '=',
                        'mesas.id_mesa'
                    )->join(
                        'restaurantes',
                        'mesas.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->join(
                        'usuario_persona',
                        'reservaciones.id_usuario_persona',
                        '=',
                        'usuario_persona.id_usuario_persona'
                    )->join(
                        'personas',
                        'usuario_persona.id_persona',
                        '=',
                        'personas.id_persona'
                    )->select(
                        'reservaciones.*',
                        'mesas.id_restaurante',
                        'mesas.mesa',
                        'restaurantes.restaurante',
                        'usuario_persona.id_persona',
                        'usuario_persona.id_usuario',
                        'personas.nombres',
                        'personas.apellidos',
                    )->where(
                        'reservaciones.id_usuario_persona',
                        '=',
                        $idUserPerson
                    )->get();

                    if ($result->count() > 0) {
                        return response()->json([
                            'title' => 'HISTORICO RESERVACIONES PARA USUARIOS',
                            'success' => true,
                            'data' => $result
                        ]);
                    } else {
                        return response()->json([
                            'title' => 'HISTORICO RESERVACIONES PARA USUARIOS',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                case 2:
                    /** HISTORICO RESERVACIONES PARA RESTAURANTES */
                    Log::info($this->clazz . '->getReservations() => MSG: HISTORICO RESERVACIONES PARA RESTAURANTES');

                    $result = Reservation::query()->join(
                        'mesas',
                        'reservaciones.id_mesa',
                        '=',
                        'mesas.id_mesa'
                    )->join(
                        'restaurantes',
                        'mesas.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->join(
                        'usuario_persona',
                        'reservaciones.id_usuario_persona',
                        '=',
                        'usuario_persona.id_usuario_persona'
                    )->join(
                        'personas',
                        'usuario_persona.id_persona',
                        '=',
                        'personas.id_persona'
                    )->select(
                        'reservaciones.*',
                        'mesas.id_restaurante',
                        'mesas.mesa',
                        'restaurantes.restaurante',
                        'usuario_persona.id_persona',
                        'usuario_persona.id_usuario',
                        'personas.nombres',
                        'personas.apellidos',
                        'personas.celular',
                    )->where(
                        'mesas.id_restaurante',
                        '=',
                        $idRestaurant
                    )->get();

                    if ($result->count() > 0) {
                        return response()->json([
                            'title' => 'HISTORICO RESERVACIONES PARA RESTAURANTES',
                            'success' => true,
                            'data' => $result
                        ]);
                    } else {
                        return response()->json([
                            'title' => 'HISTORICO RESERVACIONES PARA RESTAURANTES',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                case 3:
                    /** TRAE UN OBJETO RESERVACION ESTO POR ID RESERVACION PARA USUARIOS */
                    Log::info($this->clazz . '->getReservations() => MSG: TRAE UN OBJETO RESERVACION ESTO POR ID RESERVACION PARA USUARIOS');

                    $result = Reservation::query(
                    )->join(
                        'mesas',
                        'reservaciones.id_mesa',
                        '=',
                        'mesas.id_mesa'
                    )->join(
                        'restaurantes',
                        'mesas.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->join(
                        'usuario_persona',
                        'reservaciones.id_usuario_persona',
                        '=',
                        'usuario_persona.id_usuario_persona'
                    )->join(
                        'personas',
                        'usuario_persona.id_persona',
                        '=',
                        'personas.id_persona'
                    )->select(
                        'reservaciones.*',
                        'mesas.id_restaurante',
                        'mesas.mesa',
                        'restaurantes.restaurante',
                        'usuario_persona.id_persona',
                        'usuario_persona.id_usuario',
                        'personas.nombres',
                        'personas.apellidos',
                    )->where(
                        'reservaciones.id_usuario_persona',
                        '=',
                        $idUserPerson
                    )->where(
                        'reservaciones.id_reservacion',
                        '=',
                        $idReservation
                    )->first();

                    if($result){
                        return response()->json([
                            'title' => 'TRAE UN OBJETO RESERVACION ESTO POR ID RESERVACION PARA USUARIOS',
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN OBJETO RESERVACION ESTO POR ID RESERVACION PARA USUARIOS',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                case 4:
                    /** TRAE UN OBJETO RESERVACION ESTO POR ID RESERVACION PARA RESTAURANTES */
                    Log::info($this->clazz . '->getReservations() => MSG: TRAE UN OBJETO RESERVACION ESTO POR ID RESERVACION PARA RESTAURANTES');

                    $result = Reservation::query()->join(
                        'mesas',
                        'reservaciones.id_mesa',
                        '=',
                        'mesas.id_mesa'
                    )->join(
                        'restaurantes',
                        'mesas.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->join(
                        'usuario_persona',
                        'reservaciones.id_usuario_persona',
                        '=',
                        'usuario_persona.id_usuario_persona'
                    )->join(
                        'personas',
                        'usuario_persona.id_persona',
                        '=',
                        'personas.id_persona'
                    )->select(
                        'reservaciones.*',
                        'mesas.id_restaurante',
                        'mesas.mesa',
                        'restaurantes.restaurante',
                        'usuario_persona.id_persona',
                        'usuario_persona.id_usuario',
                        'personas.nombres',
                        'personas.apellidos',
                        'personas.celular',
                    )->where(
                        'mesas.id_restaurante',
                        '=',
                        $idRestaurant
                    )->where(
                        'reservaciones.id_reservacion',
                        '=',
                        $idReservation
                    )->first();

                    if($result){
                        return response()->json([
                            'title' => 'TRAE UN OBJETO RESERVACION ESTO POR ID RESERVACION PARA USUARIOS',
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN OBJETO RESERVACION ESTO POR ID RESERVACION PARA USUARIOS',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
                default:
                    /** TRAE UN LISTADO DE RESERVACION ACTIVAS E INACTIVAS SIN CONDICION */
                    Log::info($this->clazz.'->getReservations() => MSG: TRAE UN LISTADO DE RESERVACION ACTIVAS E INACTIVAS SIN CONDICION');

                    $result = Reservation::query()->join(
                        'mesas',
                        'reservaciones.id_mesa',
                        '=',
                        'mesas.id_mesa'
                    )->join(
                        'restaurantes',
                        'mesas.id_restaurante',
                        '=',
                        'restaurantes.id_restaurante'
                    )->join(
                        'usuario_persona',
                        'reservaciones.id_usuario_persona',
                        '=',
                        'usuario_persona.id_usuario_persona'
                    )->join(
                        'personas',
                        'usuario_persona.id_persona',
                        '=',
                        'personas.id_persona'
                    )->select(
                        'reservaciones.*',
                        'mesas.id_restaurante',
                        'mesas.mesa',
                        'restaurantes.restaurante',
                        'usuario_persona.id_persona',
                        'usuario_persona.id_usuario',
                        'personas.nombres',
                        'personas.apellidos',
                        'personas.celular',
                    )->whereIn(
                        'reservaciones.estado',
                        array(
                            Constantes::ESTADO_ACTIVO,
                            Constantes::ESTADO_INACTIVO
                        )
                    )->get();

                    if($result->count() > 0){
                        return response()->json([
                            'title' => 'TRAE UN LISTADO DE RESERVACION ACTIVAS E INACTIVAS SIN CONDICION',
                            'success' => true,
                            'data' => $result
                        ]);
                    }else{
                        return response()->json([
                            'title' => 'TRAE UN LISTADO DE RESERVACION ACTIVAS E INACTIVAS SIN CONDICION',
                            'success' => false,
                            'data' => 'No Se Encontraron Registros!!!'
                        ]);
                    }
                    break;
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->getReservations() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storeReservation(Request $request) : JsonResponse
    {
        Log::info($this->clazz.'->storeReservation() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_mesa' => 'required',
                'id_usuario_persona' => 'required',
                'codigo_reservacion' => 'required',
                'fecha_reservacion' => 'required',
                'hora_inicio' => 'required',
                'hora_fin' => 'required',
                'notas' => 'required',
                'estatus_reservacion' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = Reservation::create($request->all());

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Guardado Correctamente!!!',
                    'result' => $result
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->storeReservation() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function updateReservation(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'->updateReservation() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_mesa' => 'required',
                'id_usuario_persona' => 'required',
                'codigo_reservacion' => 'required',
                'fecha_reservacion' => 'required',
                'hora_inicio' => 'required',
                'hora_fin' => 'required',
                'notas' => 'required',
                'estatus_reservacion' => 'required',
                'estado' => 'required',
                'usuario_modificacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $reservation = Reservation::find($id);

                if($reservation){

                    $reservation->id_mesa = $request->id_mesa;
                    $reservation->id_usuario_persona = $request->id_usuario_persona;
                    $reservation->codigo_reservacion = $request->codigo_reservacion;
                    $reservation->fecha_reservacion = $request->fecha_reservacion;
                    $reservation->hora_inicio = $request->hora_inicio;
                    $reservation->hora_fin = $request->hora_fin;
                    $reservation->estatus_reservacion = $request->estatus_reservacion;

                    $reservation->estado = $request->estado;
                    $reservation->usuario_modificacion = $request->usuario_modificacion;

                    $result = $reservation->save();

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
            Log::error($this->clazz.'->updateReservation() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deleteReservation($id) : JsonResponse
    {
        Log::info($this->clazz.'->deleteReservation() => init');
        try {
            $reservation = Reservation::find($id);

            if($reservation){
                $reservation->estado = Constantes::ESTADO_ELIMINADO;
                $result = $reservation->save();

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
            Log::error($this->clazz.'->deleteReservation() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
