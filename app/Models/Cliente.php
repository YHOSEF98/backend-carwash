<?php

namespace App\Models;

use App\Enums\TipoPersonaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;
    protected $casts = [
        'tipo_persona'=> TipoPersonaEnum::class
    ];
    protected $fillable = [
        'tipos_documentos_id',
        'numero_documento',
        'razon_social',
        'nombre_comercial',
        'telefono',
        'email',
        'direccion',
        'codigo_pais',
        'departamento',
        'ciudad',
        'codigo_municipio',
        'regimen_fiscal',
        'tipo_persona',
        'obligaciones',
        'empresa_id'
    ];
}
