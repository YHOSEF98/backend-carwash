<?php

namespace App\Enums;

enum EstadoVentaEnum: string{
    case LAVANDO = 'lavando';
    case ESPERA = 'espera';
    case TERMINADO = 'terminado';
    case FACTURADO = 'facturado';
}
