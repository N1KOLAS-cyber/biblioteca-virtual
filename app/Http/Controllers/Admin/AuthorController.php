<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.authors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.authors.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar que se cree bien
        $request->validate([
            'nombre' => 'required|string|max:255',
            'biografia' => 'nullable|string',
            'foto' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'nacionalidad' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'is_verified' => 'boolean',
        ]);

        //generar slug si no se proporciona
        $slug = $request->slug ?: Str::slug($request->nombre);
        
        //asegurar que el slug sea único
        $originalSlug = $slug;
        $counter = 1;
        while (Author::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        //si pasa la validacion creara el autor
        Author::create([
            'nombre' => $request->nombre,
            'slug' => $slug,
            'biografia' => $request->biografia,
            'foto' => $request->foto,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'nacionalidad' => $request->nacionalidad,
            'user_id' => $request->user_id,
            'is_verified' => $request->boolean('is_verified'), // Admin acepta/verifica al autor
        ]);

        //variable de un solo uso para alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Autor creado correctamente',
            'text' => 'El autor ha sido creado exitosamente',
        ]);

        // Redireccionara a la tabla principal
        return redirect()->route('admin.authors.index')->with('success', 'Author created successfully.');
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
    public function edit(Author $author)
    {
        $users = User::all();
        return view('admin.authors.edit', compact('author', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar el autor
        $author = Author::findOrFail($id);

        //validar que se actualice bien (si falla, Laravel redirige automáticamente con withInput())
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'biografia' => 'nullable|string',
            'foto' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'nacionalidad' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'is_verified' => 'boolean',
        ]);

        //preparar datos para actualizar
        $data = [
            'nombre' => $request->nombre,
            'biografia' => $request->biografia,
            'foto' => $request->foto,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'nacionalidad' => $request->nacionalidad,
            'user_id' => $request->user_id,
            'is_verified' => $request->boolean('is_verified'), // Admin acepta/verifica al autor
        ];

        //si el nombre cambió, actualizar slug
        if ($author->nombre !== $request->nombre) {
            $slug = Str::slug($request->nombre);
            $originalSlug = $slug;
            $counter = 1;
            while (Author::where('slug', $slug)->where('id', '!=', $author->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        //verificar si hubo cambios
        $newIsVerified = $request->boolean('is_verified');
        $currentIsVerified = (bool) $author->is_verified;
        
        $hasChanges = $author->nombre !== $request->nombre
            || $author->biografia !== $request->biografia
            || $author->foto !== $request->foto
            || $author->fecha_nacimiento?->format('Y-m-d') !== $request->fecha_nacimiento
            || $author->nacionalidad !== $request->nacionalidad
            || $author->user_id != $request->user_id
            || $currentIsVerified !== $newIsVerified;

        //si no hay cambios, mostrar alerta
        if (!$hasChanges) {
            session()->flash('swal', [
                'icon' => 'info',
                'title' => 'Sin cambios',
                'text' => 'No se detectaron modificaciones',
            ]);
            return redirect()->route('admin.authors.index');
        }

        //si pasa la validacion actualizara el autor
        $author->update($data);

        //variable de un solo uso para alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Autor actualizado correctamente',
            'text' => 'El autor ha sido actualizado exitosamente',
        ]);

        // Redireccionara a la tabla principal
        return redirect()->route('admin.authors.index')->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        //verificar si tiene libros asociados (se implementará cuando se cree el modelo Book)
        // if ($author->books()->count() > 0) {
        //     session()->flash('swal', [
        //         'icon' => 'error',
        //         'title' => 'Error',
        //         'text' => 'No puedes eliminar un autor que tiene libros asociados',
        //     ]);
        //     return redirect()->route('admin.authors.index');
        // }

        //borrar el elemento
        $author->delete();

        //alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Autor eliminado correctamente',
            'text' => 'El autor ha sido eliminado exitosamente',
        ]);

        //redireccionar al mismo
        return redirect()->route('admin.authors.index');
    }
}

