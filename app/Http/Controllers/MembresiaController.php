<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use App\Models\Cliente;
use App\Models\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembresiaController extends Controller
{
    public function index()
    {
        $membresias = Membresia::with(['cliente', 'plan'])
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('membresias/Index', [
            'membresias' => $membresias,
        ]);
    }

    public function create()
    {
        return Inertia::render('membresias/Create', [
            'clientes' => Cliente::where('activo', true)
                ->orderBy('apellido')
                ->orderBy('nombre')
                ->get(),

            'planes' => Plan::where('activo', true)
                ->orderBy('nombre')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'plan_id' => ['required', 'exists:planes,id'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_vencimiento' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:activa,vencida,cancelada'],
        ]);

        Membresia::create($validated);

        return redirect()
            ->route('membresias.index')
            ->with('success', 'Membresía creada correctamente.');
    }
}