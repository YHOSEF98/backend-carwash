<?php

namespace App\Models;

use App\Enums\MovimientoInventarioEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimientoinventario extends Model
{
    /** @use HasFactory<\Database\Factories\MovimientoinventarioFactory> */
    use HasFactory;

    protected $casts = [
        'tipo_movimiento' => MovimientoInventarioEnum::class
    ];

    protected $fillable = [
        'producto_id',
        'tipo_movimiento',
        'cantidad',
        'observacion',
        'empresa_id',
    ];
}
