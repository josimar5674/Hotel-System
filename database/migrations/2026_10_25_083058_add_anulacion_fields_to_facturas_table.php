<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    /**
     * Agregar campos para registrar la anulación
     * de facturas sin eliminar sus registros.
     */
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->foreignId('anulada_por')
                ->nullable()
                ->after('estado')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('fecha_anulacion')
                ->nullable()
                ->after('anulada_por');
            $table->text('motivo_anulacion')
                ->nullable()
                ->after('fecha_anulacion');
        });
    }
    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('anulada_por');
            $table->dropColumn([
                'fecha_anulacion',
                'motivo_anulacion',
            ]);
        });
    }
};