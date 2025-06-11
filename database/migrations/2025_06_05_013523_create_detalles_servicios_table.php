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
        Schema::create('detalles_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('tipos_servicio_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('tipos_vehiculo_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('precio');
            $table->string('observaciones')->nullable();
            $table->foreignId('venta_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_servicios');
    }
};
