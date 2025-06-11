<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesServicio extends Model
{
    /** @use HasFactory<\Database\Factories\DetallesServicioFactory> */
    use HasFactory;
    protected $fillable = [
        'empleado_id',
        'tipos_servicio_id',
        'tipos_vehiculo_id',
        'precio',
        'observaciones',
        'venta_id',
        'empresa_id',
    ];
}
