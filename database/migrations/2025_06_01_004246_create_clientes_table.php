<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipos_documentos_id')->constrained('tipos_documentos')->onDelete('cascade')->onUpdate('cascade');
            $table->string('numero_documento');
            $table->string('razon_social');
            $table->string('nombre_comercial')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('direccion');
            $table->string('codigo_pais', 2)->default('CO');
            $table->string('departamento');
            $table->string('ciudad');
            $table->string('codigo_municipio', 10); // Código DIVIPOLA del municipio
            $table->string('regimen_fiscal'); // Simplificado, Común, etc.
            $table->string('tipo_persona'); // Natural o Jurídica
            $table->string('obligaciones')->nullable();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade'); // Ej: R-99-PN
            $table->timestamps();

            $table->unique(['empresa_id', 'numero_documento'], 'empresa_documento_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
