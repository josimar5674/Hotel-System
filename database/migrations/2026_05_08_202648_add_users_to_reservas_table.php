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
    Schema::table('reservas', function (Blueprint $table) {

        $table->foreignId('usuario_checkin_id')
              ->nullable()
              ->constrained('users');

        $table->foreignId('usuario_checkout_id')
              ->nullable()
              ->constrained('users');

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            //
        });
    }
};
