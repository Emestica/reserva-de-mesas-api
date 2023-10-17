<?php

namespace App\Repositories;

use App\Interfaces\IRolesRepository;
use App\Models\Roles;
use App\Utilities\Constantes;
use Exception;
use Illuminate\Support\Facades\Log;

class RolesRepository implements IRolesRepository
{
    private $sClass = RolesRepository::class;

    public function getRoles($opcion)
    {
        Log::info($this->sClass."->getRoles() => Inicio");
        switch ($opcion) {
            case 1:
                return Roles::query()->where('estado', '=', Constantes::ESTADO_ACTIVO);
                break;
            case 2:
                return Roles::query()->where('estado', '=', Constantes::ESTADO_INACTIVO);
                break;
            default:
                return Roles::all();
                break;
        }
    }

    public function saveRol($roles)
    {
        try {
            Log::info($this->sClass."->saveRol() => Inicio");
            //Log::info($this->sClass."->saveRol() => arreglo: ".$roles);
            //$result = $roles->save();
            $result = Roles::create($roles);
            Log::info($this->sClass."->saveRol() => Fin");
            return $result;
        } catch (Exception $e) {
            Log::error($this->sClass."->saveRol() => Error: ".$e);
            Log::error($this->sClass."->saveRol() => ----------------------------------------- <=");
            Log::error($this->sClass."->saveRol() => Error: ".$e->getMessage());
        }
    }

    public function deleteRol($id)
    {
        Log::info($this->sClass."->deleteRol() => Inicio");
        $objeto = new Roles();
        $objeto->update();
        $result = Roles::where()->update();
        Log::info($this->sClass."->deleteRol() => Fin");
        return $result;
    }

    public function updateRol($datos, $id)
    {
        Log::info($this->sClass."->updateRol() => Inicio");
        $result = null;
        try {
            $result = Roles::where('id_rol', $id)->update($datos);
        } catch (Exception $e) {
            Log::error($this->sClass."->updateRol() => Exception: ".$e->getMessage());
        }
        Log::info($this->sClass."->updateRol() => Inicio");
        return $result;
    }
}
?>