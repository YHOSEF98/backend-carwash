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
        Schema::create('precio_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipos_servicios_id')->constrained('tipos_servicios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('tipos_vehiculos_id')->constrained('tipos_vehiculos')->onDelete('cascade')->onUpdate('cascade');
            $table->float('precio');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precio_servicios');
    }
};
