<?php

namespace App\Http\Controllers;

use App\Interfaces\IRolesRepository;
use App\Models\Roles;
use App\Utilities\Constantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\Concerns\Has;

class RolesController extends Controller
{
    private $sClass = RolesController::class;
    private IRolesRepository $repositorio;

    public function __construct(IRolesRepository $rolesRepositorio)
    {
        $this->repositorio = $rolesRepositorio;
    }

    public function show(Request $request)
    {
        Log::info($this->sClass."->show() => Inicio\n".$request);
        $opcion = $request->opcion;
        Log::info($this->sClass."->show() => opcion: ".$opcion);
        Log::info($this->sClass."->show() => Fin");
        return response()->json([
            'data' => $this->repositorio->getRoles($opcion)
        ]);
    }

    public function guardarTest(Request $request){
        $rol = new Roles();
        $rol->rol = "Test Rol";
        $rol->descripcion = "Test Desc";
        $rol->usuario_creacion = "root";
        //$result = $rol->save();
        //$result = Roles::where('rol', 'OwnerRestaurant')->first();

        return response()->json([
            //"result" => $result,
            "rol" => $rol,
            "password" => Hash::make("123456789"),
            "check" => Hash::check("123456789", "$2y$10$3lnNdTtdqrRna/40kL6Wd.V.sXzA96UTLZTOvPPdxxVKNU9.CdA1m"),
            "other" => password_verify("123456789", "$2y$10$3lnNdTtdqrRna/40kL6Wd.V.sXzA96UTLZTOvPPdxxVKNU9.CdA1m"),
            "password_has" => password_hash("123456789", PASSWORD_ARGON2ID),
            "md5" => md5("admin")
        ]);
    }

    public function store(Request $request)
    {
        Log::info($this->sClass."->store() => Inicio\n".$request);
        $objeto = [
            "rol" => $request->rol,
            "descripcion" => $request->descripcion,
            "estado" => $request->estado,
            "usuario_creacion" => Constantes::USUARIO_POR_DEFECTO
        ];
        //Log::info($this->sClass."->store() => objeto-rol: ".$objeto);
        Log::info($this->sClass."->store() => Fin");
        return response()->json([
            'data' => $this->repositorio->saveRol($objeto)
        ]);
    }

    public function update(Request $request)
    {
        Log::info($this->sClass."->update() => Inicio\n".$request);

        $id = $request->id;

        $jsonAsString = json_encode($request->data);
        $datos = json_decode($jsonAsString, true);

        $result = $this->repositorio->updateRol($datos, $id);

        Log::info($this->sClass."->update() => Fin");
        return response()->json([ 'data' => $result ]);
    }
}
