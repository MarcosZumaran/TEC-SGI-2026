<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Incidentes/tickets de soporte técnico (núcleo ITIL del sistema).
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique();

            // Usuario que reporta el incidente (obligatorio, no se puede eliminar).
            $table->foreignId('reporter_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Técnico asignado (opcional).
            $table->foreignId('technician_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Activo afectado (opcional: el incidente puede no estar ligado a un equipo).
            $table->foreignId('asset_id')
                ->nullable()
                ->constrained('assets')
                ->nullOnDelete();

            $table->foreignId('location_id')
                ->constrained('locations')
                ->restrictOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            // Problema (causa raíz) asociado, si existe.
            $table->foreignId('problem_id')
                ->nullable()
                ->constrained('problems')
                ->nullOnDelete();

            $table->enum('priority', ['Baja', 'Media', 'Alta', 'Critica'])
                ->default('Media');

            $table->enum('status', ['Abierto', 'En Progreso', 'En Espera', 'Resuelto', 'Cerrado'])
                ->default('Abierto');

            $table->enum('waiting_reason', ['user', 'third_party', 'internal_dependency', 'none'])
                ->default('none');

            $table->text('description');
            $table->text('solution')->nullable();

            // Marcas de tiempo para métricas de SLA.
            $table->dateTime('first_response_at')->nullable();
            $table->dateTime('resolved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices para los filtros más usados del panel.
            $table->index('status');
            $table->index('priority');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
