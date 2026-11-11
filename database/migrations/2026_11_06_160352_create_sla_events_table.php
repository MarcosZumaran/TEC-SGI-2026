<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eventos de SLA: avisos, incumplimientos y cumplimientos.
     */
    public function up(): void
    {
        Schema::create('sla_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->enum('event_type', ['warning', 'breach', 'met']);
            $table->integer('escalation_level')->default(1);

            // Usuario notificado; si se elimina, el evento se conserva.
            $table->foreignId('notified_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('created_at')->useCurrent();

            $table->index('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_events');
    }
};
