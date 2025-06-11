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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('placa');
            $table->foreignId('cliente_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->date('fecha');
            $table->integer('num_factura')->nullable();
            $table->foreignId('metodo_pago_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('tipo_venta')->nullable();//poner el enum
            $table->string('estado_venta')->nullable();//poner el enum
            $table->decimal('subtotal',10,2)->nullable();
            $table->decimal('iva',10,2)->nullable();
            $table->decimal('total',10,2)->nullable();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
