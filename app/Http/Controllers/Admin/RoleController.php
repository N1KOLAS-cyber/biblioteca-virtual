<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar que se cree bien
        $request->validate(['name' => 'required|unique:roles,name']);

        //si pasa la validacion creara el rol
        Role::create(['name' => $request->name]);

        //variable de un solo uso para alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Rol creado correctamente',
            'text' => 'El rol ha sido creado exitosamente',
        ]);

        // Redireccionara a la tabla principal
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
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
    public function edit(Role $role)
    {
        //restringir la accion para los primeros 4 roles fijos
        if($role->id <=4){
            //variable de un solo uso
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Erorr',
                'text' => 'No puedes editar este rol',
            ]);
            return redirect()->route('admin.roles.index');
        }
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar el rol
        $role = Role::findOrFail($id);

        //validar que se actualice bien
        $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);

        //si el campo no cambio, no lo actualices
        if($role->name === $request->name) {
            session()->flash('swal', [
                'icon' => 'info',
                'title' => 'Sin cambios',
                'text' => 'No se detectaron modificaciones',
            ]);

            //redirecciona a la tabla principal
            return redirect()->route('admin.roles.index');
        }

        //si pasa la validacion actualizara el rol
        $role->update(['name' => $request->name]);

        //variable de un solo uso para alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Rol actualizado correctamente',
            'text' => 'El rol ha sido actualizado exitosamente',
        ]);

        // Redireccionara a la tabla principal
        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //restringir la accion para los primeros 4 roles fijos
        if($role->id <=4) {
            //variable de un solo uso
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Erorr',
                'text' => 'No puedes eliminar este rol',
            ]);
            return redirect()->route('admin.roles.index');
        }
        //borrar el elemento
        $role->delete();

        //alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Rol eliminado correctamente',
            'text' => 'El rol ha sido eliminado exitosamente',
        ]);

        //redireccionar al mismo
        return redirect()->route('admin.roles.index');
    }
}
