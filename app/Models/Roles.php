<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $primaryKey = 'id_rol';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'rol',
        'descripcion',
        'usuario_creacion'
    ];
}
