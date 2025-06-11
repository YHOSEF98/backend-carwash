<?php

namespace App\Enums\Enums;

enum EstadoProductoVentaEnum: string{
    case PENDIENTE = 'pendiente';
    case PAGADO = 'pagado';
}
