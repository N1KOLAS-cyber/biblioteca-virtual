<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar que se cree bien
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        //generar slug si no se proporciona
        $slug = $request->slug ?: Str::slug($request->nombre);
        
        //asegurar que el slug sea único
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        //si pasa la validacion creara la categoria
        Category::create([
            'nombre' => $request->nombre,
            'slug' => $slug,
            'descripcion' => $request->descripcion,
            'is_active' => $request->boolean('is_active'),
            'order' => 0, // Mantener por compatibilidad pero siempre 0
        ]);

        //variable de un solo uso para alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Categoría creada correctamente',
            'text' => 'La categoría ha sido creada exitosamente',
        ]);

        // Redireccionara a la tabla principal
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
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
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar la categoria
        $category = Category::findOrFail($id);

        //validar que se actualice bien (si falla, Laravel redirige automáticamente con withInput())
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        //preparar datos para actualizar
        $data = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'is_active' => $request->boolean('is_active'),
        ];

        //si el nombre cambió, actualizar slug
        if ($category->nombre !== $request->nombre) {
            $slug = Str::slug($request->nombre);
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        //verificar si hubo cambios
        $newIsActive = $request->boolean('is_active');
        $currentIsActive = (bool) $category->is_active;
        
        $hasChanges = $category->nombre !== $request->nombre
            || $category->descripcion !== $request->descripcion
            || $currentIsActive !== $newIsActive;

        //si no hay cambios, mostrar alerta
        if (!$hasChanges) {
            session()->flash('swal', [
                'icon' => 'info',
                'title' => 'Sin cambios',
                'text' => 'No se detectaron modificaciones',
            ]);
            return redirect()->route('admin.categories.index');
        }

        //si pasa la validacion actualizara la categoria
        $category->update($data);

        //variable de un solo uso para alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Categoría actualizada correctamente',
            'text' => 'La categoría ha sido actualizada exitosamente',
        ]);

        // Redireccionara a la tabla principal
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //borrar el elemento
        $category->delete();

        //alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Categoría eliminada correctamente',
            'text' => 'La categoría ha sido eliminada exitosamente',
        ]);

        //redireccionar al mismo
        return redirect()->route('admin.categories.index');
    }
}

