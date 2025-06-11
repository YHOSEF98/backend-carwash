<?php

namespace App\Models;

use App\Enums\Enums\EstadoProductoVentaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesProducto extends Model
{
    /** @use HasFactory<\Database\Factories\DetallesProductoFactory> */
    use HasFactory;

    protected $casts = [
        'estado_venta_producto'=> EstadoProductoVentaEnum::class
    ];
    protected $fillable = [
        'producto_id',
        'cantidad',
        'subtotal',
        'estado_venta_producto',
        'venta_id',
        'empresa_id',
    ];
}
