<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

interface Estadisticas {
    clientes: number
    membresias_activas: number
    ingresos_mes: string | number
    asistencias_hoy: number
}

interface Cliente {
    id: number
    nombre: string
    apellido: string
    ci: string
    foto: string | null
}

interface Plan {
    id: number
    nombre: string
}

interface Membresia {
    id: number
    fecha_vencimiento: string
    cliente: Cliente
    plan: Plan
}

interface Pago {
    id: number
    monto: string | number
    metodo_pago: string
    fecha_pago: string
    numero_recibo: string
    cliente: Cliente
    membresia: {
        plan: Plan
    }
}

const props = defineProps<{
    estadisticas: Estadisticas
    proximasVencer: Membresia[]
    ultimosPagos: Pago[]
    ultimosClientes: Cliente[]
}>()

const formatearFecha = (fecha: string) => {
    const partes = fecha.substring(0, 10).split('-')

    if (partes.length !== 3) {
        return fecha
    }

    return `${partes[2]}/${partes[1]}/${partes[0]}`
}

const nombreMetodo = (metodo: string) => {
    const metodos: Record<string, string> = {
        efectivo: 'Efectivo',
        qr: 'QR',
        tarjeta: 'Tarjeta',
        transferencia: 'Transferencia',
    }

    return metodos[metodo] ?? metodo
}
</script>

<template>
    <Head title="Dashboard" />

    <div class="min-h-screen bg-slate-50">
        <!-- Encabezado -->
        <div class="border-b border-slate-200 bg-white">
            <div class="px-6 py-7 lg:px-8">
                <div
                    class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <p
                            class="text-sm font-bold uppercase tracking-widest !text-indigo-600"
                        >
                            FITCONTROL
                        </p>

                        <h1 class="mt-1 text-3xl font-bold text-slate-900">
                            Dashboard
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Resumen general de tu gimnasio.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <Link
                            href="/clientes/create"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            + Cliente
                        </Link>

                        <Link
                            href="/membresias/create"
                            class="rounded-xl !bg-indigo-600 px-4 py-2.5 text-sm font-semibold !text-white shadow-sm transition hover:!bg-indigo-700"
                        >
                            + Membresía
                        </Link>

                        <Link
                            href="/pagos/create"
                            class="rounded-xl !bg-emerald-600 px-4 py-2.5 text-sm font-semibold !text-white shadow-sm transition hover:!bg-emerald-700"
                        >
                            + Pago
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <main class="px-6 py-8 lg:px-8">
            <!-- Estadísticas -->
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <!-- Clientes -->
                <div
                    class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="flex items-center justify-between">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-2xl"
                        >
                            👥
                        </div>

                        <span
                            class="text-xs font-semibold uppercase tracking-wide text-slate-400"
                        >
                            Clientes
                        </span>
                    </div>

                    <p class="mt-5 text-3xl font-bold text-slate-900">
                        {{ props.estadisticas.clientes }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Clientes registrados
                    </p>
                </div>

                <!-- Membresías -->
                <div
                    class="group rounded-2xl border border-emerald-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="flex items-center justify-between">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-2xl"
                        >
                            🎫
                        </div>

                        <span
                            class="text-xs font-semibold uppercase tracking-wide text-emerald-500"
                        >
                            Activas
                        </span>
                    </div>

                    <p class="mt-5 text-3xl font-bold text-slate-900">
                        {{ props.estadisticas.membresias_activas }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Membresías vigentes
                    </p>
                </div>

                <!-- Ingresos -->
                <div
                    class="group rounded-2xl border border-blue-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="flex items-center justify-between">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-2xl"
                        >
                            💰
                        </div>

                        <span
                            class="text-xs font-semibold uppercase tracking-wide text-blue-500"
                        >
                            Este mes
                        </span>
                    </div>

                    <p class="mt-5 text-3xl font-bold text-slate-900">
                        Bs
                        {{ Number(props.estadisticas.ingresos_mes).toFixed(2) }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Ingresos registrados
                    </p>
                </div>

                <!-- Asistencias -->
                <div
                    class="group rounded-2xl border border-amber-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="flex items-center justify-between">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-2xl"
                        >
                            🏃
                        </div>

                        <span
                            class="text-xs font-semibold uppercase tracking-wide text-amber-500"
                        >
                            Hoy
                        </span>
                    </div>

                    <p class="mt-5 text-3xl font-bold text-slate-900">
                        {{ props.estadisticas.asistencias_hoy }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Asistencias registradas
                    </p>
                </div>
            </div>

            <!-- Contenido -->
            <div class="mt-8 grid gap-6 xl:grid-cols-2">
                <!-- Próximos vencimientos -->
                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center justify-between border-b border-slate-200 px-6 py-5"
                    >
                        <div>
                            <h2 class="font-bold text-slate-900">
                                Próximos vencimientos
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Membresías que vencen en los próximos 7 días.
                            </p>
                        </div>

                        <span
                            class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700"
                        >
                            {{ props.proximasVencer.length }}
                        </span>
                    </div>

                    <div
                        v-if="props.proximasVencer.length > 0"
                        class="divide-y divide-slate-100"
                    >
                        <div
                            v-for="membresia in props.proximasVencer"
                            :key="membresia.id"
                            class="flex items-center justify-between gap-4 px-6 py-4 transition hover:bg-slate-50"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-700"
                                >
                                    {{
                                        membresia.cliente.nombre
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                </div>

                                <div>
                                    <p
                                        class="font-semibold text-slate-900"
                                    >
                                        {{ membresia.cliente.nombre }}
                                        {{ membresia.cliente.apellido }}
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        {{ membresia.plan.nombre }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-sm font-bold text-amber-600">
                                    {{
                                        formatearFecha(
                                            membresia.fecha_vencimiento,
                                        )
                                    }}
                                </p>

                                <p class="text-xs text-slate-400">
                                    Vencimiento
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="px-6 py-10 text-center"
                    >
                        <div class="text-4xl">
                            🎉
                        </div>

                        <p
                            class="mt-3 font-semibold text-slate-900"
                        >
                            No hay vencimientos próximos
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            No existen membresías por vencer en los próximos
                            7 días.
                        </p>
                    </div>
                </section>

                <!-- Últimos pagos -->
                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center justify-between border-b border-slate-200 px-6 py-5"
                    >
                        <div>
                            <h2 class="font-bold text-slate-900">
                                Últimos pagos
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Movimientos registrados recientemente.
                            </p>
                        </div>

                        <Link
                            href="/pagos"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-800"
                        >
                            Ver todos →
                        </Link>
                    </div>

                    <div
                        v-if="props.ultimosPagos.length > 0"
                        class="divide-y divide-slate-100"
                    >
                        <div
                            v-for="pago in props.ultimosPagos"
                            :key="pago.id"
                            class="flex items-center justify-between gap-4 px-6 py-4 transition hover:bg-slate-50"
                        >
                            <div>
                                <p class="font-semibold text-slate-900">
                                    {{ pago.cliente.nombre }}
                                    {{ pago.cliente.apellido }}
                                </p>

                                <p class="text-sm text-slate-500">
                                    {{ pago.membresia.plan.nombre }}
                                    ·
                                    {{ nombreMetodo(pago.metodo_pago) }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p
                                    class="font-bold text-emerald-600"
                                >
                                    Bs {{ Number(pago.monto).toFixed(2) }}
                                </p>

                                <p class="text-xs text-slate-400">
                                    {{ formatearFecha(pago.fecha_pago) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="px-6 py-10 text-center"
                    >
                        <div class="text-4xl">
                            💰
                        </div>

                        <p
                            class="mt-3 font-semibold text-slate-900"
                        >
                            No hay pagos registrados
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Los pagos aparecerán aquí cuando sean registrados.
                        </p>
                    </div>
                </section>
            </div>

            <!-- Últimos clientes -->
            <section
                class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <div
                    class="flex items-center justify-between border-b border-slate-200 px-6 py-5"
                >
                    <div>
                        <h2 class="font-bold text-slate-900">
                            Últimos clientes registrados
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Los clientes agregados recientemente.
                        </p>
                    </div>

                    <Link
                        href="/clientes"
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-800"
                    >
                        Ver clientes →
                    </Link>
                </div>

                <div
                    v-if="props.ultimosClientes.length > 0"
                    class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5"
                >
                    <div
                        v-for="cliente in props.ultimosClientes"
                        :key="cliente.id"
                        class="rounded-xl border border-slate-200 p-4 transition hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-700"
                            >
                                {{
                                    cliente.nombre
                                        .charAt(0)
                                        .toUpperCase()
                                }}
                            </div>

                            <div class="min-w-0">
                                <p
                                    class="truncate font-semibold text-slate-900"
                                >
                                    {{ cliente.nombre }}
                                </p>

                                <p
                                    class="truncate text-sm text-slate-500"
                                >
                                    {{ cliente.apellido }}
                                </p>
                            </div>
                        </div>

                        <p
                            class="mt-3 text-xs text-slate-400"
                        >
                            CI: {{ cliente.ci }}
                        </p>
                    </div>
                </div>

                <div
                    v-else
                    class="px-6 py-10 text-center"
                >
                    <div class="text-4xl">
                        👥
                    </div>

                    <p
                        class="mt-3 font-semibold text-slate-900"
                    >
                        No hay clientes registrados
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        Registra tu primer cliente para comenzar.
                    </p>
                </div>
            </section>
        </main>
    </div>
</template>