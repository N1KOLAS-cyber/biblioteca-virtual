<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titulo',
        'slug',
        'sinopsis',
        'author_id',
        'published_by_user_id',
        'paginas',
        'idioma',
        'año_publicacion',
        'editorial',
        'is_free',
        'is_featured',
        'reads_count',
        'downloads_count',
        'favorites_count',
        'reviews_count',
        'average_rating',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'año_publicacion' => 'integer',
        'paginas' => 'integer',
        'average_rating' => 'decimal:2',
    ];

    // Autor del libro
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // Usuario que lo publicó (si es escritor del sistema)
    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by_user_id');
    }

    // Categorías
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_category')
            ->withTimestamps();
    }

    // Usar slug para route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Generar slug automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            if (empty($book->slug)) {
                $book->slug = Str::slug($book->titulo);
            }
        });

        static::updating(function ($book) {
            if ($book->isDirty('titulo') && empty($book->slug)) {
                $book->slug = Str::slug($book->titulo);
            }
        });
    }
}
