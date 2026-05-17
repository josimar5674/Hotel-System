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
    Schema::create('reservas', function (Blueprint $table) {

    $table->id();

    $table->foreignId('cliente_id')
          ->constrained('clientes');

    $table->foreignId('habitacion_id')
          ->constrained('habitaciones');

    $table->date('fecha_entrada');

    $table->date('fecha_salida');

    $table->integer('cantidad_personas')
          ->default(1);

$table->enum('estado', [

    'reservada',

    'checkin',

    'checkout',

    'cancelada'

])->default('reservada');

    $table->text('observaciones')
          ->nullable();

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
