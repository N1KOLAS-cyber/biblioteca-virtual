<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Author extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'biografia',
        'foto',
        'fecha_nacimiento',
        'nacionalidad',
        'redes_sociales',
        'user_id',
        'is_verified',
        'books_count',
        'followers_count',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'redes_sociales' => 'array',
        'is_verified' => 'boolean',
    ];

    // Si es escritor del sistema
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Libros del autor (se implementará cuando se cree el modelo Book)
    // public function books()
    // {
    //     return $this->hasMany(Book::class);
    // }

    // Helpers
    public function isSystemWriter(): bool
    {
        return $this->user_id !== null;
    }

    // Generar slug automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($author) {
            if (empty($author->slug)) {
                $author->slug = Str::slug($author->nombre);
            }
        });

        static::updating(function ($author) {
            if ($author->isDirty('nombre') && empty($author->slug)) {
                $author->slug = Str::slug($author->nombre);
            }
        });
    }
}
