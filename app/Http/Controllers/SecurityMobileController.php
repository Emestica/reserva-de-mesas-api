<?php

namespace App\Http\Controllers;

use App\Models\Personas;
use App\Models\Roles;
use App\Models\TipoPersonas;
use App\Models\UsuarioPersona;
use App\Models\UsuarioRol;
use App\Models\Usuarios;
use App\Utilities\Constantes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SecurityMobileController extends Controller
{
    private string $clazz = SecurityMobileController::class;

    public function loginUserMobile(Request $request): JsonResponse
    {
        Log::info('->loginUserMobile() => init');
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
                    Constantes::CHANNEL_MOBILE
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

                        return response()->json([
                            'success' => true,
                            'data_user_object' => $user,
                            'data_user_person-object' => $userPerson
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
            Log::error('->loginUserMobile() => error: '.$th->getMessage());
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function registerUserMobile(Request $request):JsonResponse
    {
        Log::info($this->clazz.'->registerUserMobile() => INIT DAEMON MOBILE <=');

        $resUser = $this->validateRequiredUser($request);
        $resPerson = $this->validateRequiredPerson($request);

        if($resUser['result'] === true && $resPerson['result'] === true)
        {
            if($this->validateUniqueEmail($request->input('user.correo_electronico'))){

                Log::info($this->clazz.'->registerUserMobile() => CREATE OBJECT USER <=');
                $user = $this->createObjectUser($request);
                Log::info($user);

                Log::info($this->clazz.'->registerUserMobile() => CREATE OBJECT PERSON <=');
                $person = $this->createObjectPerson($request);
                Log::info($person);

                Log::info($this->clazz.'->registerUserMobile() => MOBILE STARTING SAVE IN DATABASE <=');

                $resultUser = $user->save();
                Log::info($this->clazz.'->registerUserMobile() => USER CREATED:          '.$resultUser.' <=');
                Log::info($this->clazz.'->registerUserMobile() => USER ID CREATED:       '.$user->id_usuario.' <=');

                $resultPerson = $person->save();
                Log::info($this->clazz.'->registerUserMobile() => PERSON CREATED:        '.$resultPerson.' <=');
                Log::info($this->clazz.'->registerUserMobile() => PERSON ID CREATED:     '.$person->id_persona.' <=');

                Log::info($this->clazz.'->registerUserMobile() => MOBILE STARTING SAVE USER ROL <=');

                $rolObject = $this->getRolForMobileChannel();

                $userRol = new UsuarioRol();
                $userRol->id_usuario = $user->id_usuario;
                $userRol->id_rol = $rolObject->id_rol;
                $userRol->usuario_creacion = Constantes::USUARIO_POR_DEFECTO;
                $resultUserRol = $userRol->save();
                Log::info($this->clazz.'->registerUserMobile() => USER ROL CREATED:     '.$resultUserRol.' <=');
                Log::info($this->clazz.'->registerUserMobile() => USER ROL ID CREATED:  '.$userRol->id_usuario_rol.' <=');

                Log::info($this->clazz.'->registerUserMobile() => STARTING SAVE USER PERSON <=');
                $userPerson = new UsuarioPersona();
                $userPerson->id_usuario = $user->id_usuario;
                $userPerson->id_persona = $person->id_persona;
                $userPerson->usuario_creacion = Constantes::USUARIO_POR_DEFECTO;
                $resultUserPerson = $userPerson->save();
                Log::info($this->clazz.'->registerUserMobile() => USER PERSON CREATED:      '.$resultUserPerson.' <=');
                Log::info($this->clazz.'->registerUserMobile() => USER PERSON ID CREATED:   '.$userPerson->id_usuario_persona.' <=');

                Log::info($this->clazz.'->registerUserMobile() => END SAVE IN DATABASE <=');

                Log::info($this->clazz.'->registerUserMobile() => END DAEMON CLIENT <=');

                return response()->json([
                    'success' => true,
                    'user-object-created' => $user,
                    'person-object-created' => $person,
                    'user-role-object-created' => $userRol,
                    'user-person-object-created' => $userPerson
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
            ], 200);
        }
    }

    public function getRolForMobileChannel()
    {
        return Roles::where(Constantes::ROL_FIELD, Constantes::ROL_MOBILE_DEFAULT)->first();
    }

    public function getTypePersonForMobileChannel()
    {
        return TipoPersonas::where(Constantes::TYPE_PERSON_FIELD, Constantes::TYPE_PERSON_MOBILE_DEFAULT)->first();
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

    public function validateUniqueEmail($email): bool
    {
        $result = Usuarios::query(
        )->where(
            'correo_electronico',
            '=',
            $email
        )->where(
            'channel',
            '=',
            Constantes::CHANNEL_MOBILE
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
        $user->channel = Constantes::CHANNEL_MOBILE;
        //$userGenerate = $this->generateNameUser($request->input('person.nombres'), $request->input('person.apellidos'));
        //Log::info($this->clazz.'->createObjectUser() => name user generate: '.$userGenerate);
        //$user->usuario = $userGenerate;
        return $user;
    }

    public function createObjectPerson(Request $request): Personas
    {
        $typePerson = $this->getTypePersonForMobileChannel();
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
}
