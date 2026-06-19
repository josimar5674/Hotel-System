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
        Schema::create('cierres_diarios', function (Blueprint $table) {

            $table->id();

            $table->date('fecha')->unique();

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal(
                'total_facturado',
                12,
                2
            )->default(0);

            $table->decimal(
                'efectivo_facturado',
                12,
                2
            )->default(0);

            $table->decimal(
                'tarjeta_facturado',
                12,
                2
            )->default(0);

            $table->decimal(
                'transferencia_facturado',
                12,
                2
            )->default(0);

            $table->decimal(
                'efectivo_contado',
                12,
                2
            )->default(0);

            $table->decimal(
                'diferencia',
                12,
                2
            )->default(0);

            $table->integer(
                'cantidad_facturas'
            )->default(0);

            $table->text(
                'observaciones'
            )->nullable();

            $table->timestamp(
                'fecha_cierre'
            )->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'cierres_diarios'
        );
    }
};