<?php

namespace App\Http\Controllers;

use App\Interfaces\IRolesRepository;
use App\Utilities\Constantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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