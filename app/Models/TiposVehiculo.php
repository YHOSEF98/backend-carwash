<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposVehiculo extends Model
{
    /** @use HasFactory<\Database\Factories\TiposVehiculoFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'empresa_id'
    ];
}
