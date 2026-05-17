<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('factura_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('forma_pago_id')
                  ->constrained('forma_pagos');

            $table->decimal('monto', 10, 2);

            $table->string('referencia')
                  ->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};