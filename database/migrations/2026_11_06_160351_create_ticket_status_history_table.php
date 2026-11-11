<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Historial de cambios de estado de cada ticket.
     */
    public function up(): void
    {
        Schema::create('ticket_status_history', function (Blueprint $table) {
            $table->id();

            // Al eliminar el ticket se elimina su historial.
            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);

            // Usuario que realizó el cambio de estado.
            $table->foreignId('changed_by')
                ->constrained('users')
                ->restrictOnDelete();

            // Solo se registra la fecha de creación (no hay updated_at en el ER).
            $table->timestamp('created_at')->useCurrent();

            $table->index('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_status_history');
    }
};
