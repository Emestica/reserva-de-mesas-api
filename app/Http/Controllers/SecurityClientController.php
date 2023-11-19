<?php

namespace App\Http\Controllers;

use App\Models\Personas;
use App\Models\Restaurantes;
use App\Models\Roles;
use App\Models\TipoPersonas;
use App\Models\UsuarioPersona;
use App\Models\UsuarioRestaurante;
use App\Models\UsuarioRol;
use App\Models\Usuarios;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SecurityClientController extends Controller
{
    private string $clazz = SecurityClientController::class;

    public function loginUserWeb(Request $request): JsonResponse
    {
        Log::info('->loginUserWeb() => init');
        Log::info($request);
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);

            if($validation->fails()){
                return response()->json([
                    'success' => false,
                    'data' => 'Faltan Datos Requeridos!!!',
                    'result' => $validation->messages()
                ]);
            } else {
                $user = Usuarios::query(
                )->where(
                    'channel',
                    '=',
                    Constantes::CHANNEL_WEB
                )->where(
                    'correo_electronico',
                    '=',
                    $request->email
                )->first();

                if($user){
                    if(md5($request->password) == $user->contrasenia){

                        $userPerson = UsuarioPersona::query(
                        )->join(
                            'personas',
                            'usuario_persona.id_persona',
                            '=',
                            'personas.id_persona'
                        )->select(
                            'usuario_persona.*',
                            'personas.nombres',
                            'personas.apellidos',
                            'personas.celular',
                            'personas.estado as persona_estado',
                        )->where(
                            'usuario_persona.id_usuario',
                            '=',
                            $user->id_usuario
                        )->where(
                            'usuario_persona.estado',
                            '=',
                            Constantes::ESTADO_ACTIVO
                        )->first();

                        $userRestaurant = UsuarioRestaurante::query(
                        )->join(
                            'restaurantes',
                            'usuario_restaurante.id_restaurante',
                            '=',
                            'restaurantes.id_restaurante'
                        )->select(
                            'usuario_restaurante.*',
                            'restaurantes.nombre_legal',
                            'restaurantes.restaurante',
                            'restaurantes.telefono',
                            'restaurantes.estado as restaurante_estado',
                        )->where(
                            'usuario_restaurante.id_usuario',
                            '=',
                            $user->id_usuario
                        )->where(
                            'usuario_restaurante.estado',
                            '=',
                            Constantes::ESTADO_ACTIVO
                        )->first();

                        return response()->json([
                            'success' => true,
                            'data_user_object' => $user,
                            'data_user_person_object' => $userPerson,
                            'data_user_restaurant_object' => $userRestaurant
                        ]);
                    }else{
                        return response()->json([
                            'success' => false,
                            'data' => 'Contraseña Incorrecta!!!'
                        ]);
                    }
                }else{
                    return response()->json([
                        'success' => false,
                        'data' => 'Usuario No Encontrado!!!'
                    ]);
                }
            }
        } catch (\Throwable $th){
            Log::error('->loginUserWeb() => error: '.$th->getMessage());
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function registerUserClient(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->registerUserClient() => INIT DAEMON CLIENT <=');

        $resUser = $this->validateRequiredUser($request);
        $resPerson = $this->validateRequiredPerson($request);
        $resRestaurant = $this->validateRequiredRestaurant($request);

        if($resUser['result'] === true && $resPerson['result'] === true && $resRestaurant['result'] === true)
        {
            if($this->validateUniqueEmail($request->input('user.correo_electronico'))){

                Log::info($this->clazz.'->registerUserClient() => CREATE OBJECT USER <=');
                $user = $this->createObjectUser($request);
                Log::info($user);

                Log::info($this->clazz.'->registerUserClient() => CREATE OBJECT PERSON <=');
                $person = $this->createObjectPerson($request);
                Log::info($person);

                Log::info($this->clazz.'->registerUserClient() => CREATE OBJECT RESTAURANT <=');
                $restaurant = $this->createObjectRestaurant($request);
                Log::info($restaurant);

                Log::info($this->clazz.'->registerUserClient() => STARTING SAVE IN DATABASE <=');

                $resultUser = $user->save();
                Log::info($this->clazz.'->registerUserClient() => USER CREATED:          '.$resultUser.' <=');
                Log::info($this->clazz.'->registerUserClient() => USER ID CREATED:       '.$user->id_usuario.' <=');

                $resultPerson = $person->save();
                Log::info($this->clazz.'->registerUserClient() => PERSON CREATED:        '.$resultPerson.' <=');
                Log::info($this->clazz.'->registerUserClient() => PERSON ID CREATED:     '.$person->id_persona.' <=');

                $resultRestaurant = $restaurant->save();
                Log::info($this->clazz.'->registerUserClient() => RESTAURANT CREATED:    '.$resultRestaurant.' <=');
                Log::info($this->clazz.'->registerUserClient() => RESTAURANT ID CREATED: '.$restaurant->id_restaurante.' <=');

                Log::info($this->clazz.'->registerUserClient() => STARTING SAVE USER ROL <=');

                $rolObject = $this->getRolForClientChannel();

                $userRol = new UsuarioRol();
                $userRol->id_usuario = $user->id_usuario;
                $userRol->id_rol = $rolObject->id_rol;
                $userRol->usuario_creacion = Constantes::USUARIO_POR_DEFECTO;
                $resultUserRol = $userRol->save();
                Log::info($this->clazz.'->registerUserClient() => USER ROL CREATED:     '.$resultUserRol.' <=');
                Log::info($this->clazz.'->registerUserClient() => USER ROL ID CREATED:  '.$userRol->id_usuario_rol.' <=');

                Log::info($this->clazz.'->registerUserClient() => STARTING SAVE USER PERSON <=');
                $userPerson = new UsuarioPersona();
                $userPerson->id_usuario = $user->id_usuario;
                $userPerson->id_persona = $person->id_persona;
                $userPerson->usuario_creacion = Constantes::USUARIO_POR_DEFECTO;
                $resultUserPerson = $userPerson->save();
                Log::info($this->clazz.'->registerUserClient() => USER PERSON CREATED:      '.$resultUserPerson.' <=');
                Log::info($this->clazz.'->registerUserClient() => USER PERSON ID CREATED:   '.$userPerson->id_usuario_persona.' <=');

                Log::info($this->clazz.'->registerUserClient() => STARTING SAVE USER RESTAURANT <=');
                $userRestaurant = new UsuarioRestaurante();
                $userRestaurant->id_usuario = $user->id_usuario;
                $userRestaurant->id_restaurante = $restaurant->id_restaurante;
                $userRestaurant->usuario_creacion = Constantes::USUARIO_POR_DEFECTO;
                $resultUserPerson = $userRestaurant->save();
                Log::info($this->clazz.'->registerUserClient() => USER RESTAURANT CREATED:      '.$resultUserPerson.' <=');
                Log::info($this->clazz.'->registerUserClient() => USER RESTAURANT ID CREATED:   '.$userRestaurant->id_usuario_restaurante.' <=');

                Log::info($this->clazz.'->registerUserClient() => END SAVE IN DATABASE <=');

                Log::info($this->clazz.'->registerUserClient() => END DAEMON CLIENT <=');

                return response()->json([
                    'success' => true,
                    'user-object-created' => $user,
                    'person-object-created' => $person,
                    'restaurant-object-created' => $restaurant,
                    'user-role-object-created' => $userRol,
                    'user-person-object-created' => $userPerson,
                    'user-restaurant-object-created' => $userRestaurant,
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'data' => 'Correo Electronico Ya Registrado!!!'
                ], 200);
            }
        }else{
            return response()->json([
                'success' => false,
                'data-user' => $resUser['message'],
                'data-person' => $resPerson['message'],
                'data-restaurant' => $resRestaurant['message']
            ], 200);
        }
    }

    public function getRolForClientChannel()
    {
        return Roles::where(Constantes::ROL_FIELD, Constantes::ROL_CLIENT_DEFAULT)->first();
    }

    public function getTypePersonForClientChannel()
    {
        return TipoPersonas::where(Constantes::TYPE_PERSON_FIELD, Constantes::TYPE_PERSON_CLIENT_DEFAULT)->first();
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
            //'person.telefono' => 'required',
            'person.celular' => 'required',
            //'person.correo_electronico' => 'required',
            //'person.direccion' => 'required',
            'person.usuario_creacion' => 'required',
            //'person.id_tipo_persona' => 'required'
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
            //'restaurant.direccion' => 'required',
            'restaurant.telefono' => 'required',
            //'restaurant.celular' => 'required',
            'restaurant.correo' => 'required',
            //'restaurant.pagina_web' => 'required',
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
        )->where(
            'channel',
            '=',
            Constantes::CHANNEL_WEB
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
        $user->contrasenia = md5($request->input('user.contrasenia'));
        $user->channel = Constantes::CHANNEL_WEB;
        //$userGenerate = $this->generateNameUser($request->input('person.nombres'), $request->input('person.apellidos'));
        //Log::info($this->clazz.'->createObjectUser() => name user generate: '.$userGenerate);
        //$user->usuario = $userGenerate;
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
        $typePerson = $this->getTypePersonForClientChannel();
        $person = new Personas();
        $person->nombres = $request->input('person.nombres');
        $person->apellidos = $request->input('person.apellidos');
        $person->fecha_nacimiento = $request->input('person.fecha_nacimiento');
        //$person->telefono = $request->input('person.telefono');
        $person->celular = $request->input('person.celular');
        $person->correo_electronico = $request->input('user.correo_electronico');
        //$person->direccion = $request->input('person.direccion');
        $person->usuario_creacion = $request->input('usuario_creacion.nombres');
        $person->id_tipo_persona = $typePerson->id_tipo_persona;
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
        //$restaurant->celular = $request->input('restaurant.celular');
        $restaurant->correo = $request->input('restaurant.correo');
        $restaurant->pagina_web = $request->input('restaurant.pagina_web');
        $restaurant->usuario_creacion = $request->input('restaurant.usuario_creacion');
        $restaurant->id_municipio = $request->input('restaurant.id_municipio');
        return $restaurant;
    }
}
