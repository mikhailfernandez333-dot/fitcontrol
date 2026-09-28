<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlanController extends Controller
{
    public function index(): Response
    {
        $planes = Plan::orderBy('id', 'desc')->get();

        return Inertia::render('planes/Index', [
            'planes' => $planes,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('planes/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'duracion_dias' => ['required', 'integer', 'min:1'],
            'precio' => ['required', 'numeric', 'min:0'],
            'activo' => ['boolean'],
        ]);

        Plan::create($validated);

        return redirect()
            ->route('planes.index')
            ->with('success', 'Plan creado correctamente.');
            
    }

public function edit(Plan $plan): Response
{
    return Inertia::render('planes/Edit', [
        'plan' => $plan,
    ]);
}

public function update(Request $request, Plan $plan): RedirectResponse
{
    $validated = $request->validate([
        'nombre' => ['required', 'string', 'max:100'],
        'descripcion' => ['nullable', 'string', 'max:500'],
        'duracion_dias' => ['required', 'integer', 'min:1'],
        'precio' => ['required', 'numeric', 'min:0'],
        'activo' => ['boolean'],
    ]);

    $plan->update($validated);

    return redirect()
        ->route('planes.index')
        ->with('success', 'Plan actualizado correctamente.');
}

public function destroy(Plan $plan): RedirectResponse
{
    $plan->delete();

    return redirect()
        ->route('planes.index')
        ->with('success', 'Plan eliminado correctamente.');
}

}