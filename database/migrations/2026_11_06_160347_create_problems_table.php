<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Problemas (ITIL): causas raíz que pueden agrupar varios incidentes.
     */
    public function up(): void
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description');
            $table->text('root_cause')->nullable();
            $table->text('workaround')->nullable();

            $table->enum('status', ['Abierto', 'En Análisis', 'Resuelto', 'Cerrado'])
                ->default('Abierto');

            // Usuario que registra el problema (no se puede eliminar si tiene problemas).
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            // Técnico asignado; si el usuario se elimina, el problema queda sin asignar.
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('problems');
    }
};
