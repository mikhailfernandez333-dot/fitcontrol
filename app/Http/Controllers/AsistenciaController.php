<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AsistenciaController extends Controller
{
    public function index()
    {
        $asistencias = Asistencia::with('cliente')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_entrada', 'desc')
            ->get();

        return Inertia::render('asistencias/Index', [
            'asistencias' => $asistencias,
        ]);
    }

    public function create()
    {
        $clientes = Cliente::where('activo', true)
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return Inertia::render('asistencias/Create', [
            'clientes' => $clientes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'fecha' => ['required', 'date'],
            'hora_entrada' => ['required', 'date_format:H:i'],
        ]);

        $yaExiste = Asistencia::where('cliente_id', $validated['cliente_id'])
            ->where('fecha', $validated['fecha'])
            ->exists();

        if ($yaExiste) {
            return back()->withErrors([
                'cliente_id' => 'Este cliente ya tiene una asistencia registrada para este día.',
            ]);
        }

        Asistencia::create($validated);

        return redirect()
            ->route('asistencias.index')
            ->with('success', 'Asistencia registrada correctamente.');
    }
}