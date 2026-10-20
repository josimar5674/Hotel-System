<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_fiscal', function (Blueprint $table) {
            $table->string('rango_autorizado')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_fiscal', function (Blueprint $table) {
            $table->dropColumn('rango_autorizado');
        });
    }
};