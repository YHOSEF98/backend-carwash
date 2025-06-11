<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposServicio extends Model
{
    /** @use HasFactory<\Database\Factories\TiposServicioFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'empresa_id',
    ];
}
