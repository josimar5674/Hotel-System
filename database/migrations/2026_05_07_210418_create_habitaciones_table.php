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
     Schema::create('habitaciones', function (Blueprint $table) {
    $table->id();

    $table->string('numero')->unique();

    $table->enum('tipo', [
        'simple',
        'doble',
        'matrimonial',
        'suite'
    ]);

    $table->decimal('precio', 10, 2);

  $table->enum('estado', [
    'disponible',
    'ocupada',
    'limpieza',
    'mantenimiento'
]);

    $table->text('descripcion')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};
