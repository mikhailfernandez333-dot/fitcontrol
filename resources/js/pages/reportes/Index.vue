<script setup lang="ts">
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
    AlertTriangle,
    BarChart3,
    CalendarCheck,
    CreditCard,
    DollarSign,
    TrendingUp,
    Users,
    Wallet,
} from '@lucide/vue'

interface Cliente {
    id: number
    nombre: string
    apellido: string
    ci: string
}

interface Plan {
    id: number
    nombre: string
    precio: string | number
}

interface Membresia {
    id: number
    fecha_vencimiento: string
    cliente: Cliente
    plan: Plan
}

interface ClienteFrecuente {
    total: number
    cliente: Cliente
}

interface PlanVendido {
    total: number
    plan: Plan
}

interface Estadisticas {
    total_clientes: number
    clientes_activos: number
    clientes_inactivos: number
    nuevos_clientes: number
    membresias_activas: number
    membresias_vencidas: number
    ingresos_mes: string | number
    cantidad_pagos_mes: number
    asistencias_hoy: number
    asistencias_mes: number
}

interface MetodosPago {
    efectivo: number
    qr: number
    tarjeta: number
    transferencia: number
}

const props = defineProps<{
    estadisticas: Estadisticas
    metodosPago: MetodosPago
    proximasVencer: Membresia[]
    clienteMasFrecuente: ClienteFrecuente | null
    planesMasVendidos: PlanVendido[]
}>()

const mostrarTodosVencimientos = ref(false)

const proximasVencerMostradas = computed(() => {
    if (mostrarTodosVencimientos.value) {
        return props.proximasVencer
    }

    return props.proximasVencer.slice(0, 5)
})

const porcentajeClientesActivos = computed(() => {
    if (props.estadisticas.total_clientes === 0) {
        return 0
    }

    return Math.round(
        (props.estadisticas.clientes_activos /
            props.estadisticas.total_clientes) *
            100,
    )
})

const totalMetodosPago = computed(() => {
    return Object.values(props.metodosPago).reduce(
        (total, cantidad) => total + cantidad,
        0,
    )
})

const porcentajeMetodoPago = (cantidad: number) => {
    if (totalMetodosPago.value === 0) {
        return 0
    }

    return Math.round((cantidad / totalMetodosPago.value) * 100)
}

const formatearDinero = (valor: string | number) => {
    return Number(valor).toLocaleString('es-BO', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })
}

const formatearFecha = (fecha: string) => {
    const fechaCorta = fecha.substring(0, 10)
    const partes = fechaCorta.split('-')

    if (partes.length !== 3) {
        return fecha
    }

    return `${partes[2]}/${partes[1]}/${partes[0]}`
}

const diasParaVencer = (fecha: string) => {
    const hoy = new Date()
    hoy.setHours(0, 0, 0, 0)

    const vencimiento = new Date(`${fecha.substring(0, 10)}T00:00:00`)

    const diferencia =
        vencimiento.getTime() - hoy.getTime()

    return Math.ceil(diferencia / (1000 * 60 * 60 * 24))
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 p-6 lg:p-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <!-- Encabezado -->
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">
                    FITCONTROL
                </p>

                <div class="mt-1 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                            Reportes
                        </h1>

                        <p class="mt-1 text-slate-500">
                            Resumen general del funcionamiento del gimnasio.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 shadow-sm ring-1 ring-slate-200">
                        <BarChart3 class="h-5 w-5 text-blue-600" />

                        <span class="text-sm font-semibold text-slate-700">
                            Resumen actual
                        </span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas principales -->
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                <!-- Clientes -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Total clientes
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ estadisticas.total_clientes }}
                            </p>

                            <p class="mt-2 text-xs font-medium text-emerald-600">
                                {{ estadisticas.nuevos_clientes }} nuevos este mes
                            </p>
                        </div>

                        <div class="rounded-xl bg-blue-100 p-3 text-blue-600">
                            <Users class="h-6 w-6" />
                        </div>
                    </div>
                </div>

                <!-- Membresías -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Membresías activas
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ estadisticas.membresias_activas }}
                            </p>

                            <p class="mt-2 text-xs font-medium text-red-600">
                                {{ estadisticas.membresias_vencidas }} vencidas
                            </p>
                        </div>

                        <div class="rounded-xl bg-emerald-100 p-3 text-emerald-600">
                            <TrendingUp class="h-6 w-6" />
                        </div>
                    </div>
                </div>

                <!-- Ingresos -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Ingresos del mes
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                Bs {{ formatearDinero(estadisticas.ingresos_mes) }}
                            </p>

                            <p class="mt-2 text-xs font-medium text-slate-500">
                                {{ estadisticas.cantidad_pagos_mes }} pagos registrados
                            </p>
                        </div>

                        <div class="rounded-xl bg-amber-100 p-3 text-amber-600">
                            <DollarSign class="h-6 w-6" />
                        </div>
                    </div>
                </div>

                <!-- Asistencias -->
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Asistencias del mes
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ estadisticas.asistencias_mes }}
                            </p>

                            <p class="mt-2 text-xs font-medium text-blue-600">
                                {{ estadisticas.asistencias_hoy }} registradas hoy
                            </p>
                        </div>

                        <div class="rounded-xl bg-violet-100 p-3 text-violet-600">
                            <CalendarCheck class="h-6 w-6" />
                        </div>
                    </div>
                </div>

            </div>

            <!-- Segunda sección -->
            <div class="grid gap-6 lg:grid-cols-2">

                <!-- Estado de clientes -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                Estado de clientes
                            </h2>

                            <p class="text-sm text-slate-500">
                                Distribución actual
                            </p>
                        </div>

                        <Users class="h-6 w-6 text-blue-600" />
                    </div>

                    <div class="mt-6">
                        <div class="mb-2 flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-600">
                                Clientes activos
                            </span>

                            <span class="text-sm font-bold text-slate-900">
                                {{ porcentajeClientesActivos }}%
                            </span>
                        </div>

                        <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="h-full rounded-full bg-blue-600 transition-all duration-700"
                                :style="{
                                    width: `${porcentajeClientesActivos}%`,
                                }"
                            ></div>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4">

                        <div class="rounded-xl bg-emerald-50 p-4">
                            <p class="text-sm text-emerald-700">
                                Activos
                            </p>

                            <p class="mt-1 text-2xl font-bold text-emerald-800">
                                {{ estadisticas.clientes_activos }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-100 p-4">
                            <p class="text-sm text-slate-600">
                                Inactivos
                            </p>

                            <p class="mt-1 text-2xl font-bold text-slate-800">
                                {{ estadisticas.clientes_inactivos }}
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Métodos de pago -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                Métodos de pago
                            </h2>

                            <p class="text-sm text-slate-500">
                                Pagos realizados este mes
                            </p>
                        </div>

                        <Wallet class="h-6 w-6 text-emerald-600" />
                    </div>

                    <div class="mt-6 space-y-5">

                        <!-- Efectivo -->
                        <div>
                            <div class="mb-2 flex justify-between">
                                <span class="text-sm font-medium text-slate-600">
                                    Efectivo
                                </span>

                                <span class="text-sm font-bold text-slate-900">
                                    {{ metodosPago.efectivo }}
                                    ({{ porcentajeMetodoPago(metodosPago.efectivo) }}%)
                                </span>
                            </div>

                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-emerald-500"
                                    :style="{
                                        width: `${porcentajeMetodoPago(metodosPago.efectivo)}%`,
                                    }"
                                ></div>
                            </div>
                        </div>

                        <!-- QR -->
                        <div>
                            <div class="mb-2 flex justify-between">
                                <span class="text-sm font-medium text-slate-600">
                                    QR
                                </span>

                                <span class="text-sm font-bold text-slate-900">
                                    {{ metodosPago.qr }}
                                    ({{ porcentajeMetodoPago(metodosPago.qr) }}%)
                                </span>
                            </div>

                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-blue-500"
                                    :style="{
                                        width: `${porcentajeMetodoPago(metodosPago.qr)}%`,
                                    }"
                                ></div>
                            </div>
                        </div>

                        <!-- Tarjeta -->
                        <div>
                            <div class="mb-2 flex justify-between">
                                <span class="text-sm font-medium text-slate-600">
                                    Tarjeta
                                </span>

                                <span class="text-sm font-bold text-slate-900">
                                    {{ metodosPago.tarjeta }}
                                    ({{ porcentajeMetodoPago(metodosPago.tarjeta) }}%)
                                </span>
                            </div>

                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-violet-500"
                                    :style="{
                                        width: `${porcentajeMetodoPago(metodosPago.tarjeta)}%`,
                                    }"
                                ></div>
                            </div>
                        </div>

                        <!-- Transferencia -->
                        <div>
                            <div class="mb-2 flex justify-between">
                                <span class="text-sm font-medium text-slate-600">
                                    Transferencia
                                </span>

                                <span class="text-sm font-bold text-slate-900">
                                    {{ metodosPago.transferencia }}
                                    ({{ porcentajeMetodoPago(metodosPago.transferencia) }}%)
                                </span>
                            </div>

                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-amber-500"
                                    :style="{
                                        width: `${porcentajeMetodoPago(metodosPago.transferencia)}%`,
                                    }"
                                ></div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Tercera sección -->
            <div class="grid gap-6 lg:grid-cols-2">

                <!-- Membresías próximas a vencer -->
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 p-6">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                Próximas a vencer
                            </h2>

                            <p class="text-sm text-slate-500">
                                Próximos 7 días
                            </p>
                        </div>

                        <AlertTriangle class="h-6 w-6 text-amber-500" />
                    </div>

                    <div
                        v-if="proximasVencer.length === 0"
                        class="p-10 text-center"
                    >
                        <div class="mx-auto w-fit rounded-full bg-emerald-100 p-4">
                            <TrendingUp class="h-7 w-7 text-emerald-600" />
                        </div>

                        <p class="mt-4 font-semibold text-slate-900">
                            Todo está al día
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            No hay membresías próximas a vencer.
                        </p>
                    </div>

                    <div v-else class="divide-y divide-slate-100">
                        <div
                            v-for="membresia in proximasVencerMostradas"
                            :key="membresia.id"
                            class="flex items-center justify-between gap-4 p-5 transition hover:bg-slate-50"
                        >
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-900">
                                    {{ membresia.cliente.nombre }}
                                    {{ membresia.cliente.apellido }}
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ membresia.plan.nombre }}
                                </p>
                            </div>

                            <div class="shrink-0 text-right">
                                <p class="text-sm font-semibold text-slate-700">
                                    {{ formatearFecha(membresia.fecha_vencimiento) }}
                                </p>

                                <p
                                    class="mt-1 text-xs font-bold"
                                    :class="
                                        diasParaVencer(membresia.fecha_vencimiento) <= 2
                                            ? 'text-red-600'
                                            : 'text-amber-600'
                                    "
                                >
                                    {{
                                        diasParaVencer(membresia.fecha_vencimiento) === 0
                                            ? 'Vence hoy'
                                            : diasParaVencer(membresia.fecha_vencimiento) === 1
                                              ? 'Vence mañana'
                                              : `En ${diasParaVencer(membresia.fecha_vencimiento)} días`
                                    }}
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="proximasVencer.length > 5"
                            class="p-4 text-center"
                        >
                            <button
                                type="button"
                                class="text-sm font-semibold text-blue-600 transition hover:text-blue-700"
                                @click="mostrarTodosVencimientos = !mostrarTodosVencimientos"
                            >
                                {{
                                    mostrarTodosVencimientos
                                        ? 'Mostrar menos'
                                        : 'Ver todas'
                                }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cliente más frecuente -->
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                Cliente más frecuente
                            </h2>

                            <p class="text-sm text-slate-500">
                                Basado en asistencias del mes
                            </p>
                        </div>

                        <CalendarCheck class="h-6 w-6 text-violet-600" />
                    </div>

                    <div
                        v-if="clienteMasFrecuente"
                        class="mt-6 rounded-2xl bg-violet-50 p-6"
                    >
                        <div class="flex items-center gap-4">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-violet-200 text-2xl font-bold text-violet-700">
                                {{ clienteMasFrecuente.cliente.nombre.charAt(0) }}
                            </div>

                            <div>
                                <p class="text-xl font-bold text-slate-900">
                                    {{ clienteMasFrecuente.cliente.nombre }}
                                    {{ clienteMasFrecuente.cliente.apellido }}
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    CI: {{ clienteMasFrecuente.cliente.ci }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between rounded-xl bg-white p-4">
                            <span class="text-sm font-medium text-slate-500">
                                Asistencias este mes
                            </span>

                            <span class="text-2xl font-bold text-violet-700">
                                {{ clienteMasFrecuente.total }}
                            </span>
                        </div>
                    </div>

                    <div
                        v-else
                        class="mt-6 rounded-2xl bg-slate-50 p-8 text-center"
                    >
                        <CalendarCheck class="mx-auto h-10 w-10 text-slate-300" />

                        <p class="mt-3 font-semibold text-slate-700">
                            Todavía no hay asistencias
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Registra asistencias para generar este reporte.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Planes más utilizados -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-blue-100 p-3 text-blue-600">
                            <BarChart3 class="h-6 w-6" />
                        </div>

                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                Planes más utilizados
                            </h2>

                            <p class="text-sm text-slate-500">
                                Cantidad de membresías registradas por plan
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    v-if="planesMasVendidos.length === 0"
                    class="p-10 text-center"
                >
                    <p class="font-semibold text-slate-700">
                        No hay datos de planes todavía.
                    </p>
                </div>

                <div v-else class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="item in planesMasVendidos"
                        :key="item.plan.id"
                        class="rounded-xl border border-slate-200 p-5 transition duration-200 hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div class="rounded-lg bg-slate-100 p-2">
                                <CreditCard class="h-5 w-5 text-slate-600" />
                            </div>

                            <span class="text-2xl font-bold text-blue-600">
                                {{ item.total }}
                            </span>
                        </div>

                        <h3 class="mt-4 font-bold text-slate-900">
                            {{ item.plan.nombre }}
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            membresías registradas
                        </p>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex flex-wrap gap-3">
                <Link
                    href="/clientes"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    <Users class="h-4 w-4" />
                    Ver clientes
                </Link>

                <Link
                    href="/pagos"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    <Wallet class="h-4 w-4" />
                    Ver pagos
                </Link>

                <Link
                    href="/asistencias"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    <CalendarCheck class="h-4 w-4" />
                    Ver asistencias
                </Link>
            </div>

        </div>
    </div>
</template>