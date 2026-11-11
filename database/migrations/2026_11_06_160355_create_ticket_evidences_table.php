<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Evidencias adjuntas (imágenes, PDF, etc.) de los tickets.
     */
    public function up(): void
    {
        Schema::create('ticket_evidences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->string('file_path', 255);
            $table->string('file_type', 50);

            $table->timestamp('created_at')->useCurrent();

            $table->index('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_evidences');
    }
};
