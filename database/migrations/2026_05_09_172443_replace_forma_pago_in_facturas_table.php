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
    Schema::table('facturas', function (Blueprint $table) {

        $table->dropColumn('forma_pago');

    });

    Schema::table('facturas', function (Blueprint $table) {

        $table->foreignId('forma_pago_id')
              ->nullable()
              ->constrained('forma_pagos');

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            //
        });
    }
};
