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
        Schema::create('configuracion_fiscal', function (Blueprint $table) {

            $table->id();

            // Empresa

            $table->string('nombre_negocio');

            $table->string('razon_social')
                  ->nullable();

            $table->string('rtn');

            $table->string('direccion');

            $table->string('telefono');

            $table->string('correo')
                  ->nullable();


            // Fiscal

            $table->string('cai');

            $table->string('factura_inicio');

            $table->string('factura_fin');

            $table->string('siguiente_numero');

            $table->date('fecha_limite');


            // Impuestos

            $table->decimal('impuesto_15', 5, 2)
                  ->default(15);

            $table->decimal('impuesto_18', 5, 2)
                  ->default(18);

            $table->decimal('impuesto_turismo', 5, 2)
                  ->default(4);


            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_fiscal');
    }
};