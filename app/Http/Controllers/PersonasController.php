<?php

namespace App\Http\Controllers;

use App\Models\Personas;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonasController extends Controller
{
    private string $clazz = PersonasController::class;

    public function getPersons(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->getPersons() => init');

        $option = $request->opcion;
        $state = $request->estado;
        $idPerson = $request->idpersona;
        $idTypePerson = $request->idtipopersona;

        Log::info($this->clazz.'->getPersons => option:         '.$option);
        Log::info($this->clazz.'->getPersons => state:          '.$state);
        Log::info($this->clazz.'->getPersons => idPerson:       '.$idPerson);
        Log::info($this->clazz.'->getPersons => idTypePerson:   '.$idTypePerson);

        try{
            switch ($option)
            {
                case 1:
                    /** TRAE UN LISTADO DE PERSONAS POR ESTADO */
                    Log::info($this->clazz.'->getPersons() => MSG: TRAE UN LISTADO DE PERSONAS POR ESTADO: '.$state);

                    $result = Personas::query(
                    )->where(
                        'estado',
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
                    /** TRAE UN OBJETO DE PERSONA POR ID */
                    Log::info($this->clazz.'->getPersons() => MSG: TRAE UN OBJETO DE PERSONA POR ID: '.$idPerson);

                    $result = Personas::query()->where(
                        'id_persona',
                        '=',
                        $idPerson
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
                    /** TRAE UN LISTADO DE PERSONAS POR ID TIPO PERSONA Y ESTADO */
                    Log::info($this->clazz.'->getRestaurants() => MSG: TRAE UN LISTADO DE RESTAURANTES POR ID MUNICIPIO Y ESTADO');
                    $result = Personas::query(
                    )->join(
                        'tipo_persona',
                        'personas.id_tipo_persona',
                        '=',
                        'tipo_persona.id_tipo_persona'
                    )->select(
                        'personas.*',
                        'tipo_persona.tipo_persona'
                    )->where(
                        'personas.id_municipio',
                        '=',
                        $idTypePerson
                    )->where(
                        'personas.estado',
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
                default:
                    /** TRAE UN LISTADO COMPLETO DE PERSONAS SIN CONDICIONES */
                    Log::info($this->clazz.'->getPersons() => MSG: TRAE UN LISTADO COMPLETO DE PERSONAS SIN CONDICIONES');

                    $result = Personas::all();

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
            Log::error($this->clazz.'->getPersons() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function storePerson(Request $request) : JsonResponse
    {
        Log::info($this->clazz.'->storePerson() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_tipo_persona' => 'required',
                'nombres' => 'required',
                'apellidos' => 'required',
                'fecha_nacimiento' => 'required',
                'edad' => 'required',
                'telefono' => 'required',
                'celular' => 'required',
                'correo_electronico' => 'required',
                'direccion' => 'required',
                'usuario_creacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $result = Personas::create($request->all());

                return response()->json([
                    'success' => true,
                    'data' => 'Registro Guardado Correctamente!!!',
                    'result' => $result
                ]);
            }
        } catch (\Throwable $throwable) {
            Log::error($this->clazz.'->storePerson() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function updatePerson(Request $request, $id) : JsonResponse
    {
        Log::info($this->clazz.'->updatePerson() => init');
        try {
            $validation = Validator::make($request->all(), [
                'id_tipo_persona' => 'required',
                'nombres' => 'required',
                'apellidos' => 'required',
                'fecha_nacimiento' => 'required',
                'edad' => 'required',
                'telefono' => 'required',
                'celular' => 'required',
                'correo_electronico' => 'required',
                'direccion' => 'required',
                'estado' => 'required',
                'usuario_modificacion' => 'required'
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => $validation->messages()
                ]);
            }else{
                $person = Personas::find($id);

                if($person){

                    $person->id_tipo_persona = $request->id_tipo_persona;
                    $person->nombres = $request->nombres;
                    $person->apellidos = $request->apellidos;
                    $person->fecha_nacimiento = $request->fecha_nacimiento;
                    $person->edad = $request->edad;
                    $person->telefono = $request->telefono;
                    $person->celular = $request->celular;
                    $person->correo_electronico = $request->correo_electronico;
                    $person->direccion = $request->direccion;

                    $person->estado = $request->estado;
                    $person->usuario_modificacion = $request->usuario_modificacion;

                    $result = $person->save();

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
            Log::error($this->clazz.'->updatePerson() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }

    public function deletePerson($id) : JsonResponse
    {
        Log::info($this->clazz.'->deletePerson() => init');
        try {
            $person = Personas::find($id);

            if($person){
                $person->estado = Constantes::ESTADO_ELIMINADO;
                $result = $person->save();

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
            Log::error($this->clazz.'->deletePerson() => error: '.$throwable->getMessage());

            return response()->json([
                'error' => $throwable->getMessage()
            ], 500);
        }
    }
}
