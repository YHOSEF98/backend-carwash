<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioServicio extends Model
{
    /** @use HasFactory<\Database\Factories\PrecioServicioFactory> */
    use HasFactory;

    protected $fillable = [
        'tipos_servicios_id',
        'tipos_vehiculos_id',
        'precio',
        'empresa_id'
    ];

}
