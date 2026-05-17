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
        Schema::create('facturas', function (Blueprint $table) {

            $table->id();

            // Relaciones

            $table->foreignId('reserva_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('cliente_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('usuario_id')
                  ->constrained('users');


            // Fiscal

            $table->string('numero_factura')
                  ->unique();

            $table->string('cai');

            $table->date('fecha_limite');


            // Montos

            $table->decimal('subtotal', 10, 2)
                  ->default(0);

            $table->decimal('impuesto_15', 10, 2)
                  ->default(0);

            $table->decimal('impuesto_18', 10, 2)
                  ->default(0);

            $table->decimal('impuesto_turismo', 10, 2)
                  ->default(0);

            $table->decimal('total_impuestos', 10, 2)
                  ->default(0);

            $table->decimal('total', 10, 2)
                  ->default(0);


            // Estado

            $table->enum('estado', [
                'emitida',
                'anulada'
            ])->default('emitida');


            $table->timestamp('fecha_emision');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};