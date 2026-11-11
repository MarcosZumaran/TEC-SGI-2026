<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Especificaciones técnicas del activo (relación 1 a 1 con assets).
     */
    public function up(): void
    {
        Schema::create('asset_specs', function (Blueprint $table) {
            $table->id();

            // unique(): garantiza que un activo solo tenga un registro de especificaciones.
            // cascade: al eliminar el activo, se eliminan sus especificaciones.
            $table->foreignId('asset_id')
                ->unique()
                ->constrained('assets')
                ->cascadeOnDelete();

            $table->string('processor', 150)->nullable();
            $table->string('ram', 50)->nullable();
            $table->string('disk', 100)->nullable();
            $table->string('os', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('mac_address', 17)->nullable();

            // El ER no define timestamps para esta tabla.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_specs');
    }
};
