<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Comentarios/bitácora de seguimiento de los tickets.
     */
    public function up(): void
    {
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->text('message');

            // Los comentarios internos solo son visibles para técnicos/admins.
            $table->boolean('is_internal')->default(false);

            $table->timestamp('created_at')->useCurrent();

            $table->index('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_comments');
    }
};
