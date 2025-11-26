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
        Schema::table('books', function (Blueprint $table) {
            // Eliminar índice que usa status y published_at
            $table->dropIndex(['status', 'published_at']);
            
            // Eliminar campos no usados
            $table->dropColumn([
                'descripcion',
                'isbn',
                'portada',
                'status',
                'rejection_reason',
                'published_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Restaurar campos eliminados
            $table->text('descripcion')->nullable()->after('slug');
            $table->string('isbn')->nullable()->after('published_by_user_id');
            $table->string('portada')->nullable()->after('editorial');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->after('portada');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('published_at')->nullable()->after('rejection_reason');
            
            // Restaurar índice
            $table->index(['status', 'published_at']);
        });
    }
};
