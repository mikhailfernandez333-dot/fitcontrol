<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClienteController extends Controller
{
    /**
     * Mostrar todos los clientes.
     */
    public function index(): Response
    {
        $clientes = Cliente::orderBy('created_at', 'desc')->get();

        return Inertia::render('clientes/Index', [
            'clientes' => $clientes,
        ]);
    }

    /**
     * Mostrar formulario para crear cliente.
     */
    public function create(): Response
    {
        return Inertia::render('clientes/Create');
    }

    /**
     * Guardar nuevo cliente.
     */
    public function store(Request $request): RedirectResponse
    {




        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'ci' => ['required', 'string', 'max:30', 'unique:clientes,ci'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'fecha_nacimiento' => ['nullable', 'date'],
           'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'activo' => ['boolean'],
        ]);
  
        if ($request->hasFile('foto')) {
    $validated['foto'] = $request->file('foto')->store('clientes', 'public');
}


        Cliente::create($validated);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    /**
     * Mostrar un cliente.
     */
    public function show(Cliente $cliente): Response
    {
        $cliente->load([
            'membresias.plan',
            'pagos',
            'asistencias',
        ]);

        return Inertia::render('clientes/Show', [
            'cliente' => $cliente,
        ]);
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Cliente $cliente): Response
    {
        return Inertia::render('clientes/Edit', [
            'cliente' => $cliente,
        ]);
    }

    /**
     * Actualizar cliente.
     */
    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'ci' => [
                'required',
                'string',
                'max:30',
                'unique:clientes,ci,' . $cliente->id,
            ],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'foto' => ['nullable', 'string', 'max:255'],
            'activo' => ['boolean'],
        ]);

        $cliente->update($validated);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Eliminar cliente.
     */
    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}