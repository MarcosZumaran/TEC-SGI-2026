<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubicaciones internas (laboratorios, oficinas, etc.) pertenecientes a una sede.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            // Si se intenta eliminar una sede con ubicaciones, la BD lo impide.
            $table->foreignId('site_id')
                ->constrained('sites')
                ->restrictOnDelete();

            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
