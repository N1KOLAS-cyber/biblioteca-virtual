<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->longText('sinopsis')->nullable(); // Contenido del libro (texto)
            $table->foreignId('author_id')->constrained()->onDelete('restrict');
            $table->foreignId('published_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // Escritor que lo subió
            $table->string('isbn')->nullable();
            $table->integer('paginas')->nullable();
            $table->string('idioma')->default('es');
            $table->year('año_publicacion')->nullable();
            $table->string('editorial')->nullable();
            
            // Archivos (solo URL, no subida)
            $table->string('portada')->nullable(); // URL de la portada
            
            // Estado
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable(); // Si fue rechazado
            $table->timestamp('published_at')->nullable(); // Fecha de publicación/aprobación
            
            // Acceso
            $table->boolean('is_free')->default(false); // Libro gratuito
            $table->boolean('is_featured')->default(false); // Destacado
            
            // Estadísticas (cache)
            $table->integer('reads_count')->default(0);
            $table->integer('downloads_count')->default(0);
            $table->integer('favorites_count')->default(0);
            $table->integer('reviews_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0); // 0.00 - 5.00
            
            $table->timestamps();
            $table->softDeletes(); // Eliminación suave
            
            // Índices
            $table->index('slug');
            $table->index('author_id');
            $table->index(['status', 'published_at']);
            $table->index('is_free');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
