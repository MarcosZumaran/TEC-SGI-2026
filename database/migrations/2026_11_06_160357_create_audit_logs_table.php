<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registro de auditoría del sistema.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Usuario responsable de la acción (null = proceso del sistema).
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('action', ['INSERT', 'UPDATE', 'DELETE']);
            $table->string('table_name', 100);
            $table->integer('record_id');

            // Valores anteriores y nuevos en formato JSON.
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Índices para consultas de auditoría por registro y por usuario.
            $table->index(['table_name', 'record_id'], 'idx_audit_table_record');
            $table->index('user_id', 'idx_audit_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
