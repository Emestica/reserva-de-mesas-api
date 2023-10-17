<?php 

namespace App\Interfaces;

use App\Models\Roles;

interface IRolesRepository {
    public function getRoles($opcion);
    public function saveRol($arreglo);
    public function deleteRol($id);
    public function updateRol($datos, $id);
}
?>