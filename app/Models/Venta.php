<?php

namespace App\Models;

use App\Enums\EstadoVentaEnum;
use App\Enums\TipoVentaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    /** @use HasFactory<\Database\Factories\VentaFactory> */
    use HasFactory;

    protected $casts = [
    'tipo_venta' => TipoVentaEnum::class,
    'estado_venta' => EstadoVentaEnum::class
    ];

    protected $fillable = [
        'placa',
        'cliente_id',
        'fecha',
        'num_factura',
        'metodo_pago_id',
        'tipo_venta',
        'estado_venta',
        'subtotal',
        'iva',
        'total',
        'empresa_id'
    ];
}
