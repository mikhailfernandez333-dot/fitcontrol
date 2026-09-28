<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Membresia;
use App\Models\Pago;
use App\Models\Asistencia;
use Carbon\Carbon;
use Inertia\Inertia;

class ReporteController extends Controller
{
    public function index()
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();
        $hoy = Carbon::today();

        // Clientes
        $totalClientes = Cliente::count();

        $clientesActivos = Cliente::where('activo', true)->count();

        $clientesInactivos = Cliente::where('activo', false)->count();

        $nuevosClientes = Cliente::whereBetween('created_at', [
            $inicioMes,
            $finMes,
        ])->count();

        // Membresías
        $membresiasActivas = Membresia::where('estado', 'activa')
            ->whereDate('fecha_vencimiento', '>=', $hoy)
            ->count();

        $membresiasVencidas = Membresia::where(function ($query) use ($hoy) {
            $query->where('estado', 'vencida')
                ->orWhere(function ($q) use ($hoy) {
                    $q->where('estado', 'activa')
                        ->whereDate('fecha_vencimiento', '<', $hoy);
                });
        })->count();

        $proximasVencer = Membresia::with(['cliente', 'plan'])
            ->where('estado', 'activa')
            ->whereBetween('fecha_vencimiento', [
                $hoy,
                $hoy->copy()->addDays(7),
            ])
            ->orderBy('fecha_vencimiento')
            ->limit(10)
            ->get();

        // Pagos
        $ingresosMes = Pago::whereBetween('fecha_pago', [
            $inicioMes,
            $finMes,
        ])->sum('monto');

        $cantidadPagosMes = Pago::whereBetween('fecha_pago', [
            $inicioMes,
            $finMes,
        ])->count();

        $pagosEfectivo = Pago::whereBetween('fecha_pago', [
            $inicioMes,
            $finMes,
        ])->where('metodo_pago', 'efectivo')->count();

        $pagosQr = Pago::whereBetween('fecha_pago', [
            $inicioMes,
            $finMes,
        ])->where('metodo_pago', 'qr')->count();

        $pagosTarjeta = Pago::whereBetween('fecha_pago', [
            $inicioMes,
            $finMes,
        ])->where('metodo_pago', 'tarjeta')->count();

        $pagosTransferencia = Pago::whereBetween('fecha_pago', [
            $inicioMes,
            $finMes,
        ])->where('metodo_pago', 'transferencia')->count();

        // Asistencias
        $asistenciasHoy = Asistencia::whereDate('fecha', $hoy)
            ->count();

        $asistenciasMes = Asistencia::whereBetween('fecha', [
            $inicioMes,
            $finMes,
        ])->count();

        $clienteMasFrecuente = Asistencia::select('cliente_id')
            ->whereBetween('fecha', [
                $inicioMes,
                $finMes,
            ])
            ->selectRaw('COUNT(*) as total')
            ->groupBy('cliente_id')
            ->orderByDesc('total')
            ->with('cliente')
            ->first();

        // Planes más utilizados
        $planesMasVendidos = Membresia::select('plan_id')
            ->with('plan')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('plan_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return Inertia::render('reportes/Index', [
            'estadisticas' => [
                'total_clientes' => $totalClientes,
                'clientes_activos' => $clientesActivos,
                'clientes_inactivos' => $clientesInactivos,
                'nuevos_clientes' => $nuevosClientes,
                'membresias_activas' => $membresiasActivas,
                'membresias_vencidas' => $membresiasVencidas,
                'ingresos_mes' => $ingresosMes,
                'cantidad_pagos_mes' => $cantidadPagosMes,
                'asistencias_hoy' => $asistenciasHoy,
                'asistencias_mes' => $asistenciasMes,
            ],
            'metodosPago' => [
                'efectivo' => $pagosEfectivo,
                'qr' => $pagosQr,
                'tarjeta' => $pagosTarjeta,
                'transferencia' => $pagosTransferencia,
            ],
            'proximasVencer' => $proximasVencer,
            'clienteMasFrecuente' => $clienteMasFrecuente,
            'planesMasVendidos' => $planesMasVendidos,
        ]);
    }
}