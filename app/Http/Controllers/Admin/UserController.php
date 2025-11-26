<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar que se cree bien
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|exists:roles,id',
        ]);

        //si pasa la validacion creara el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //asignar rol si se proporcionó
        if ($request->role) {
            $role = Role::findOrFail($request->role);
            $user->assignRole($role);
        }

        //variable de un solo uso para alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario creado correctamente',
            'text' => 'El usuario ha sido creado exitosamente',
        ]);

        // Redireccionara a la tabla principal
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
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
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar el usuario
        $user = User::findOrFail($id);

        //validar que se actualice bien (si falla, Laravel redirige automáticamente con withInput())
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|exists:roles,id',
        ]);

        //preparar datos para actualizar
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        //si se proporcionó una nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        //verificar si el rol cambió
        $currentRoleId = $user->roles->first()?->id;
        // Si se seleccionó un rol diferente o se quitó el rol (tenía rol y ahora no, o viceversa)
        $roleChanged = ($request->role && $currentRoleId != $request->role) 
            || (!$request->role && $currentRoleId);

        //verificar si hubo cambios en los datos
        $hasChanges = $user->name !== $request->name 
            || $user->email !== $request->email 
            || $request->filled('password')
            || $roleChanged;

        //si no hay cambios, mostrar alerta
        if (!$hasChanges) {
            session()->flash('swal', [
                'icon' => 'info',
                'title' => 'Sin cambios',
                'text' => 'No se detectaron modificaciones',
            ]);
            return redirect()->route('admin.users.index');
        }

        //si pasa la validacion actualizara el usuario
        $user->update($data);

        //actualizar rol si se proporcionó
        if ($request->role) {
            $role = Role::findOrFail($request->role);
            $user->syncRoles([$role]);
        }

        //variable de un solo uso para alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado correctamente',
            'text' => 'El usuario ha sido actualizado exitosamente',
        ]);

        // Redireccionara a la tabla principal
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //prevenir eliminación del usuario autenticado (si estoy logueado con mi cuenta, no puedo eliminarme a mí mismo)
        if ($user->id === auth()->id()) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes eliminar tu propio usuario',
            ]);
            return redirect()->route('admin.users.index');
        }

        //borrar el elemento
        $user->delete();

        //alerta
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado correctamente',
            'text' => 'El usuario ha sido eliminado exitosamente',
        ]);

        //redireccionar al mismo
        return redirect()->route('admin.users.index');
    }
}

