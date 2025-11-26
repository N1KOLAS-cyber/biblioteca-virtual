<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.books.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::all();
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.create', compact('authors', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar que se cree bien
        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'sinopsis' => 'nullable|string',
                'author_id' => 'required|exists:authors,id',
                'paginas' => 'nullable|integer|min:1',
                'idioma' => 'nullable|string|max:10',
                'año_publicacion' => 'nullable|integer|min:1000|max:' . date('Y'),
                'editorial' => 'nullable|string|max:255',
                'is_free' => 'boolean',
                'is_featured' => 'boolean',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id',
            ], [
                'titulo.required' => 'El título es obligatorio',
                'titulo.max' => 'El título no puede tener más de 255 caracteres',
                'author_id.required' => 'Debes seleccionar un autor',
                'author_id.exists' => 'El autor seleccionado no es válido',
                'paginas.integer' => 'Las páginas deben ser un número entero',
                'paginas.min' => 'Las páginas deben ser al menos 1',
                'año_publicacion.integer' => 'El año debe ser un número entero',
                'año_publicacion.min' => 'El año debe ser mayor a 1000',
                'año_publicacion.max' => 'El año no puede ser mayor al año actual',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si falla la validación, redirigir de vuelta con los datos y las variables necesarias
            $authors = Author::all();
            $categories = Category::where('is_active', true)->get();
            
            // Obtener el primer error para mostrar en SweetAlert
            $firstError = $e->validator->errors()->first();
            
            return redirect()->route('admin.books.create')
                ->withInput()
                ->with(compact('authors', 'categories'))
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error de validación',
                    'text' => $firstError,
                ]);
        }

        //generar slug si no se proporciona
        $slug = $request->slug ?: Str::slug($request->titulo);
        
        //asegurar que el slug sea único
        $originalSlug = $slug;
        $counter = 1;
        while (Book::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        //si pasa la validacion creara el libro
        try {
            $book = Book::create([
                'titulo' => $request->titulo,
                'slug' => $slug,
                'sinopsis' => $request->sinopsis,
                'author_id' => $request->author_id,
                'paginas' => $request->paginas,
                'idioma' => $request->idioma ?? 'es',
                'año_publicacion' => $request->año_publicacion,
                'editorial' => $request->editorial,
                'is_free' => $request->has('is_free') ? (bool) $request->is_free : false,
                'is_featured' => $request->has('is_featured') ? (bool) $request->is_featured : false,
            ]);

            // Asignar categorías si se proporcionaron
            if ($request->has('categories')) {
                $book->categories()->sync($request->categories);
            }

            //variable de un solo uso para alerta
            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Libro creado correctamente',
                'text' => 'El libro ha sido creado exitosamente',
            ]);

            // Redireccionara a la tabla principal
            return redirect()->route('admin.books.index');
        } catch (\Exception $e) {
            // Si hay un error al crear, mostrar alerta de error
            $authors = Author::all();
            $categories = Category::where('is_active', true)->get();
            
            return redirect()->route('admin.books.create')
                ->withInput()
                ->with(compact('authors', 'categories'))
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error al crear libro',
                    'text' => 'Hubo un problema al crear el libro. Por favor, intenta de nuevo.',
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        $authors = Author::all();
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.edit', compact('book', 'authors', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar el libro
        $book = Book::findOrFail($id);

        //validar que se actualice bien
        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'sinopsis' => 'nullable|string',
                'author_id' => 'required|exists:authors,id',
                'paginas' => 'nullable|integer|min:1',
                'idioma' => 'nullable|string|max:10',
                'año_publicacion' => 'nullable|integer|min:1000|max:' . date('Y'),
                'editorial' => 'nullable|string|max:255',
                'is_free' => 'boolean',
                'is_featured' => 'boolean',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si falla la validación, redirigir de vuelta con los datos y las variables necesarias
            $authors = Author::all();
            $categories = Category::where('is_active', true)->get();
            
            // Obtener el primer error para mostrar en SweetAlert
            $firstError = $e->validator->errors()->first();
            
            return redirect()->route('admin.books.edit', $id)
                ->withInput()
                ->with(compact('authors', 'categories'))
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error de validación',
                    'text' => $firstError,
                ]);
        }

        //verificar si hubo cambios (comparar antes de preparar datos)
        $newIsFree = $request->boolean('is_free');
        $currentIsFree = (bool) $book->is_free;
        $newIsFeatured = $request->boolean('is_featured');
        $currentIsFeatured = (bool) $book->is_featured;
        
        // Normalizar valores null y vacíos para comparación
        $normalize = function($val) {
            if ($val === null || $val === '') return null;
            if (is_numeric($val)) return (int) $val;
            return (string) $val;
        };
        
        $hasChanges = $book->titulo !== $request->titulo
            || $normalize($book->sinopsis) !== $normalize($request->sinopsis)
            || (string) $book->author_id !== (string) $request->author_id
            || $normalize($book->paginas) !== $normalize($request->paginas)
            || ($book->idioma ?? 'es') !== ($request->idioma ?? 'es')
            || $normalize($book->año_publicacion) !== $normalize($request->año_publicacion)
            || $normalize($book->editorial) !== $normalize($request->editorial)
            || $currentIsFree !== $newIsFree
            || $currentIsFeatured !== $newIsFeatured;

        //verificar cambios en categorías
        $currentCategories = $book->categories->pluck('id')->sort()->values()->toArray();
        $newCategories = $request->has('categories') ? collect($request->categories)->map(fn($id) => (int)$id)->sort()->values()->toArray() : [];
        $categoriesChanged = $currentCategories !== $newCategories;

        //si no hay cambios, mostrar alerta
        if (!$hasChanges && !$categoriesChanged) {
            session()->flash('swal', [
                'icon' => 'info',
                'title' => 'Sin cambios',
                'text' => 'No se detectaron modificaciones',
            ]);
            return redirect()->route('admin.books.index');
        }

        //preparar datos para actualizar
        $data = [
            'titulo' => $request->titulo,
            'sinopsis' => $request->sinopsis,
            'author_id' => $request->author_id,
            'paginas' => $request->paginas,
            'idioma' => $request->idioma ?? 'es',
            'año_publicacion' => $request->año_publicacion,
            'editorial' => $request->editorial,
            'is_free' => $request->has('is_free') ? (bool) $request->is_free : false,
            'is_featured' => $request->has('is_featured') ? (bool) $request->is_featured : false,
        ];

        //si el título cambió, actualizar slug
        if ($book->titulo !== $request->titulo) {
            $slug = Str::slug($request->titulo);
            $originalSlug = $slug;
            $counter = 1;
            while (Book::where('slug', $slug)->where('id', '!=', $book->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        //si pasa la validacion actualizara el libro
        $book->update($data);

        //actualizar categorías
        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        } else {
            $book->categories()->detach();
        }

        //variable de un solo uso para alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Libro actualizado correctamente',
            'text' => 'El libro ha sido actualizado exitosamente',
        ]);

        // Redireccionara a la tabla principal
        return redirect()->route('admin.books.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        
        //borrar el elemento
        $book->delete();

        //alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Libro eliminado correctamente',
            'text' => 'El libro ha sido eliminado exitosamente',
        ]);

        //redireccionar al mismo
        return redirect()->route('admin.books.index');
    }
}
