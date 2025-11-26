<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.plans.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Acción no permitida',
            'text' => 'No se pueden crear nuevos planes. Solo se pueden editar los planes existentes.',
        ]);
        return redirect()->route('admin.plans.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Acción no permitida',
            'text' => 'No se pueden crear nuevos planes. Solo se pueden editar los planes existentes.',
        ]);
        return redirect()->route('admin.plans.index');
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
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $plan = Plan::findOrFail($id);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'duration_days' => 'nullable|integer|min:1',
                'trial_days' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'order' => 'nullable|integer|min:0',
            ]);
        } catch (ValidationException $e) {
            $firstError = $e->validator->errors()->first();
            
            return redirect()->route('admin.plans.edit', $plan)
                ->withInput()
                ->with(compact('plan'))
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error de validación',
                    'text' => $firstError,
                ]);
        }

        $normalize = function($val) {
            if ($val === null || $val === '') return null;
            if (is_numeric($val)) return (float) $val;
            return (string) $val;
        };

        $newIsActive = $request->has('is_active') ? (bool) $request->is_active : true;
        $currentIsActive = (bool) $plan->is_active;

        $hasChanges = $plan->name !== $request->name
            || $normalize($plan->description) !== $normalize($request->description)
            || (float) $plan->price !== (float) $request->price
            || $normalize($plan->duration_days) !== $normalize($request->duration_days)
            || (int) ($plan->trial_days ?? 0) !== (int) ($request->trial_days ?? 0)
            || $currentIsActive !== $newIsActive
            || (int) ($plan->order ?? 0) !== (int) ($request->order ?? 0);

        if (!$hasChanges) {
            session()->flash('swal', [
                'icon' => 'info',
                'title' => 'Sin cambios',
                'text' => 'No se detectaron modificaciones',
            ]);
            return redirect()->route('admin.plans.index');
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'trial_days' => $request->trial_days ?? 0,
            'is_active' => $newIsActive,
            'order' => $request->order ?? 0,
        ];

        if ($plan->name !== $request->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;
            while (Plan::where('slug', $slug)->where('id', '!=', $plan->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        try {
            $plan->update($data);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => 'Plan actualizado correctamente',
                'text' => 'El plan ha sido actualizado exitosamente',
            ]);

            return redirect()->route('admin.plans.index');
        } catch (\Exception $e) {
            return redirect()->route('admin.plans.edit', $plan)
                ->withInput()
                ->with(compact('plan'))
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error al actualizar plan',
                    'text' => 'Hubo un problema al actualizar el plan. Por favor, intenta de nuevo.',
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'Acción no permitida',
            'text' => 'No se pueden eliminar planes. Solo se pueden editar los planes existentes.',
        ]);
        return redirect()->route('admin.plans.index');
    }
}
