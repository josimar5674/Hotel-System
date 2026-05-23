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
       Schema::create('reserva_descuentos', function (Blueprint $table) {

    $table->id();

    $table->foreignId('reserva_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('descuento_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->decimal('valor', 10, 2);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_descuentos');
    }
};
