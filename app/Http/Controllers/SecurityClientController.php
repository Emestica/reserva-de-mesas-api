<?php

namespace App\Http\Controllers;

use App\Models\Personas;
use App\Models\Restaurantes;
use App\Models\Usuarios;
use App\Utilities\Constantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SecurityClientController extends Controller
{
    private string $clazz = SecurityClientController::class;

    public function registerUserClient(Request $request)
    {
        Log::info($this->clazz.'->registerUserClient() => INIT DAEMON CLIENT <=');

        $resUser = $this->validateRequiredUser($request);
        $resPerson = $this->validateRequiredPerson($request);
        $resRestaurant = $this->validateRequiredRestaurant($request);

        if($resUser['result'] === true && $resPerson['result'] === true && $resRestaurant['result'] === true)
        {
            if($this->validateUniqueEmail($request->input('user.correo_electronico'))){

                Log::info($this->clazz.'->registerUserClient() => CREATE OBJECT USUARIO <=');
                $user = $this->createObjectUser($request);
                Log::info($user);

                Log::info($this->clazz.'->registerUserClient() => CREATE OBJECT PERSONA <=');
                $person = $this->createObjectPerson($request);
                Log::info($person);

                Log::info($this->clazz.'->registerUserClient() => CREATE OBJECT RESTAURANTE <=');
                $restaurant = $this->createObjectRestaurant($request);
                Log::info($restaurant);

            }else{
                return response()->json([
                    'success' => false,
                    'data' => 'Correo Electronico Ya Registrado!!!'
                ], 200);
            }
        }

        Log::info($this->clazz.'->registerUserClient() => END DAEMON CLIENT <=');
    }

    public function validateRequiredUser(Request $request): array
    {
        $validationUser = Validator::make($request->all(),[
            'user.correo_electronico' => 'required',
            'user.password' => 'required',
        ]);

        if(!$validationUser->fails()){
            return [
                'result' => true
            ];
        } else {
            return [
                'result' => false,
                'message' => $validationUser->messages()
            ];
        }
    }

    public function validateRequiredPerson(Request $request): array
    {
        $validationPerson = Validator::make($request->all(),[
            'person.nombres' => 'required',
            'person.apellidos' => 'required',
            'person.fecha_nacimiento' => 'required',
            'person.edad' => 'required',
            'person.telefono' => 'required',
            'person.celular' => 'required',
            'person.correo_electronico' => 'required',
            'person.direccion' => 'required',
            'person.usuario_creacion' => 'required',
            'person.id_tipo_persona' => 'required'
        ]);

        if(!$validationPerson->fails()){
            return [
                'result' => true
            ];
        } else {
            return [
                'result' => false,
                'message' => $validationPerson->messages()
            ];
        }
    }

    public function validateRequiredRestaurant(Request $request): array
    {
        $validationRestaurant = Validator::make($request->all(),[
            'restaurant.nombre_legal' => 'required',
            'restaurant.restaurante' => 'required',
            'restaurant.descripcion' => 'required',
            'restaurant.direccion' => 'required',
            'restaurant.telefono' => 'required',
            'restaurant.celular' => 'required',
            'restaurant.correo' => 'required',
            'restaurant.pagina_web' => 'required',
            'restaurant.usuario_creacion' => 'required',
            'restaurant.id_municipio' => 'required'
        ]);

        if(!$validationRestaurant->fails()){
            return [
                'result' => true
            ];
        } else {
            return [
                'result' => false,
                'message' => $validationRestaurant->messages()
            ];
        }
    }

    public function validateUniqueEmail($email): bool
    {
        $result = Usuarios::query()->where(
            'correo_electronico',
            '=',
            $email
        )->get();

        if($result->count() > 0){
            return false;
        } else {
            return true;
        }
    }

    public function createObjectUser(Request $request): Usuarios
    {
        $user = new Usuarios();
        $user->correo_electronico = $request->input('user.correo_electronico');
        $user->contrasenia = Hash::make($request->input('user.contrasenia'));
        $userGenerate = $this->generateNameUser($request->input('person.nombres'), $request->input('person.apellidos'));
        Log::info($this->clazz.'->createObjectUser() => name user generate: '.$userGenerate);
        $user->usuario = $userGenerate;
        return $user;
    }

    public function generateNameUser($nombre, $apellidos): string
    {
        $lowerCaseNombres = trim(strtolower($nombre));
        $lowerCaseApellidos = trim(strtolower($apellidos));

        if(str_contains($lowerCaseNombres, ' ')){
            $nameArray = explode(" ", $lowerCaseNombres);
            $lowerCaseNombres = $nameArray[0];
        }

        if(str_contains($lowerCaseApellidos, ' ')){
            $lastNameArray = explode(" ", $lowerCaseApellidos);
            $lowerCaseApellidos = $lastNameArray[0];
        }

        $newNameUser = $lowerCaseNombres.'.'.$lowerCaseApellidos;
        if(strlen($newNameUser) <= Constantes::USUARIO_MAX_LEN){
            return $newNameUser;
        }else{
            $newNameUser = substr($newNameUser, 0, Constantes::USUARIO_MAX_LEN);
            return $newNameUser;
        }
    }

    public function createObjectPerson(Request $request): Personas
    {
        $person = new Personas();
        $person->nombres = $request->input('person.nombres');
        $person->apellidos = $request->input('person.apellidos');
        $person->fecha_nacimiento = $request->input('person.edad');
        $person->telefono = $request->input('person.telefono');
        $person->celular = $request->input('person.celular');
        $person->correo_electronico = $request->input('person.correo_electronico');
        $person->direccion = $request->input('person.direccion');
        $person->usuario_creacion = $request->input('usuario_creacion.nombres');
        $person->id_tipo_persona = $request->input('person.id_tipo_persona');
        return $person;
    }

    public function createObjectRestaurant(Request $request)
    {
        $restaurant = new Restaurantes();
        $restaurant->nombre_legal = $request->input('restaurant.nombre_legal');
        $restaurant->restaurante = $request->input('restaurant.restaurante');
        $restaurant->descripcion = $request->input('restaurant.descripcion');
        $restaurant->direccion = $request->input('restaurant.direccion');
        $restaurant->telefono = $request->input('restaurant.telefono');
        $restaurant->celular = $request->input('restaurant.celular');
        $restaurant->correo = $request->input('restaurant.correo');
        $restaurant->pagina_web = $request->input('restaurant.pagina_web');
        $restaurant->usuario_creacion = $request->input('restaurant.usuario_creacion');
        $restaurant->id_municipio = $request->input('restaurant.id_municipio');
        return $restaurant;
    }
}
