<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cliente;
use App\Models\Membresia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['cliente', 'membresia.plan'])
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('pagos/Index', [
            'pagos' => $pagos,
        ]);
    }

    public function create()
    {
        $membresias = Membresia::with(['cliente', 'plan'])
            ->where('estado', 'activa')
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('pagos/Create', [
            'membresias' => $membresias,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'membresia_id' => ['required', 'exists:membresias,id'],
            'monto' => ['required', 'numeric', 'min:0'],
            'metodo_pago' => [
                'required',
                'in:efectivo,qr,tarjeta,transferencia',
            ],
            'fecha_pago' => ['required', 'date'],
            'numero_recibo' => ['required', 'string', 'max:100', 'unique:pagos,numero_recibo'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ]);

        Pago::create($validated);

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago registrado correctamente.');
    }
}