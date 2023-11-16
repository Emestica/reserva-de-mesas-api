<?php

namespace App\Utilities;

class Constantes
{
    const USUARIO_POR_DEFECTO = 'root';
    const USUARIO_MAX_LEN = 50;

    const CHANNEL_WEB = 'WEB';
    const CHANNEL_MOBILE = 'MOBILE';

    const ROL_FIELD = 'rol';
    const ROL_CLIENT_DEFAULT = 'OwnerRestaurant';
    const ROL_CLIENT_CUSTOM = 'EmployeeRestaurant';
    const ROL_MOBILE_DEFAULT = 'UserAppMobile';

    const TYPE_PERSON_FIELD = 'tipo_persona';
    const TYPE_PERSON_CLIENT_DEFAULT = 'Restaurantes';
    const TYPE_PERSON_MOBILE_DEFAULT = 'Clientes';

    const ESTADO_ACTIVO = 'A';
    const ESTADO_INACTIVO = 'I';
    const ESTADO_ELIMINADO = 'E';
}
?>
