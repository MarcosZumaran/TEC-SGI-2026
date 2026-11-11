<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Inventario de activos (CMDB) con jerarquía padre-hijo autorreferencial.
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code', 50)->unique();

            // Tipos de activo: PC, Laptop, Proyector, Impresora, etc.
            $table->enum('type', [
                'PC', 'LAP', 'PROY', 'IMP', 'SCAN', 'SERV', 'SWIT', 'ROUT',
                'MON', 'TEC', 'MOU', 'PAR', 'WEB', 'UPS', 'OTRO',
            ]);

            // Relación autorreferencial: un activo puede pertenecer a otro (ej. monitor -> PC).
            // Si se elimina el padre, los hijos quedan sin padre (no se borran en cascada).
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('assets')
                ->nullOnDelete();

            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->nullable();

            // Ubicación física del activo (obligatoria).
            $table->foreignId('location_id')
                ->constrained('locations')
                ->restrictOnDelete();

            // Usuario responsable del activo (opcional).
            $table->foreignId('responsible_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('status', ['Operativo', 'Con Falla', 'En Mantenimiento', 'De Baja'])
                ->default('Operativo');

            $table->date('acquisition_date')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices para búsquedas frecuentes en inventario.
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
