<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extra_reserva', function (Blueprint $table) {

            $table->id();

            $table->foreignId('reserva_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('extra_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->integer('cantidad')
                  ->default(1);

            $table->decimal('precio', 10, 2);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extra_reserva');
    }
};