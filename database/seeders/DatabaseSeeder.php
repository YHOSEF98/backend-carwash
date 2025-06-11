<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Empresa;
use App\Models\TiposDocumento;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Empresa::factory()->create([
            'id' => 1,
            'nombre' => 'Zapatocas club',
            'email' => 'empresa1@empresa.com',
        ]);
        Empresa::factory()->create([
            'id' => 2,
            'nombre' => 'La Rivera',
            'email' => 'empresa2@empresa.com',
        ]);
        User::factory()->create([
            'name' => 'Yhosef Mayorga',
            'email' => 'nicolas@example.com',
            'password' => bcrypt(123456),
            'empresa_id' => 1,
        ]);
        User::factory()->create([
            'name' => 'Andres Mayorga',
            'email' => 'andres@example.com',
            'password' => bcrypt(123456),
            'empresa_id' => 2,
        ]);
        TiposDocumento::factory()->create([
            'id' => 1,
            'codigo' => 'CC',
            'nombre' => 'Cedula de Ciudadania',
        ]);
        TiposDocumento::factory()->create([
            'id' => 2,
            'codigo' => 'CE',
            'nombre' => 'Cedula de Extranjeria',
        ]);
        TiposDocumento::factory()->create([
            'id' => 3,
            'codigo' => 'NIT',
            'nombre' => 'Nimero de Identificaion Tributaria',
        ]);
        TiposDocumento::factory()->create([
            'id' => 4,
            'codigo' => 'PA',
            'nombre' => 'Pasaporte',
        ]);
        TiposDocumento::factory()->create([
            'id' => 5,
            'codigo' => 'DIE',
            'nombre' => 'Documento de Identificacion Extranjero',
        ]);

    }
}
