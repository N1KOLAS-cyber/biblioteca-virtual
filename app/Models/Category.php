<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'icono',
        'color',
        'books_count',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'books_count' => 'integer',
        'order' => 'integer',
    ];

    // Generar slug automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->nombre);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('nombre') && empty($category->slug)) {
                $category->slug = Str::slug($category->nombre);
            }
        });
    }
}
