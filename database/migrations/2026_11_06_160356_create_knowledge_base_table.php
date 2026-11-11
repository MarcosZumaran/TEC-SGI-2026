<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Base de conocimientos: FAQs, soluciones temporales y errores conocidos.
     */
    public function up(): void
    {
        Schema::create('knowledge_base', function (Blueprint $table) {
            $table->id();

            // Si se elimina la categoría, se eliminan sus artículos de conocimiento.
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->enum('type', ['faq', 'workaround', 'known_error'])->default('faq');
            $table->string('title', 255);
            $table->text('content');
            $table->boolean('is_published')->default(true);

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_base');
    }
};
