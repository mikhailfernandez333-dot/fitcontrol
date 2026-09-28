<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Membresia;
use App\Models\Pago;
use App\Models\Asistencia;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();
        $hoy = Carbon::today();

        $clientes = Cliente::count();

        $membresiasActivas = Membresia::where('estado', 'activa')
            ->whereDate('fecha_vencimiento', '>=', $hoy)
            ->count();

        $ingresosMes = Pago::whereBetween('fecha_pago', [
            $inicioMes,
            $finMes,
        ])->sum('monto');

        $asistenciasHoy = Asistencia::whereDate('fecha', $hoy)
            ->count();

        $proximasVencer = Membresia::with(['cliente', 'plan'])
            ->where('estado', 'activa')
            ->whereBetween('fecha_vencimiento', [
                $hoy,
                $hoy->copy()->addDays(7),
            ])
            ->orderBy('fecha_vencimiento')
            ->limit(5)
            ->get();

        $ultimosPagos = Pago::with(['cliente', 'membresia.plan'])
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $ultimosClientes = Cliente::orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'estadisticas' => [
                'clientes' => $clientes,
                'membresias_activas' => $membresiasActivas,
                'ingresos_mes' => $ingresosMes,
                'asistencias_hoy' => $asistenciasHoy,
            ],

            'proximasVencer' => $proximasVencer,
            'ultimosPagos' => $ultimosPagos,
            'ultimosClientes' => $ultimosClientes,
        ]);
    }
}