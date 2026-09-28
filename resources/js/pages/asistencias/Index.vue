<script setup lang="ts">
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
    CalendarCheck,
    Clock,
    Search,
    Users,
} from '@lucide/vue'

interface Cliente {
    id: number
    nombre: string
    apellido: string
    ci: string
}

interface Asistencia {
    id: number
    fecha: string
    hora_entrada: string
    cliente: Cliente
}

const props = defineProps<{
    asistencias: Asistencia[]
}>()

const buscar = ref('')

const asistenciasFiltradas = computed(() => {
    const texto = buscar.value.toLowerCase().trim()

    if (!texto) {
        return props.asistencias
    }

    return props.asistencias.filter((asistencia) => {
        const cliente = asistencia.cliente

        return (
            `${cliente.nombre} ${cliente.apellido}`
                .toLowerCase()
                .includes(texto) ||
            cliente.ci.toLowerCase().includes(texto)
        )
    })
})

const formatearFecha = (fecha: string) => {
    const fechaCorta = fecha.substring(0, 10)
    const partes = fechaCorta.split('-')

    if (partes.length !== 3) {
        return fecha
    }

    return `${partes[2]}/${partes[1]}/${partes[0]}`
}

const formatearHora = (hora: string) => {
    return hora.substring(0, 5)
}

const totalAsistencias = computed(() => props.asistencias.length)

const asistenciasHoy = computed(() => {
    const hoy = new Date().toISOString().substring(0, 10)

    return props.asistencias.filter(
        (asistencia) => asistencia.fecha.substring(0, 10) === hoy,
    ).length
})

const clientesUnicos = computed(() => {
    return new Set(props.asistencias.map((asistencia) => asistencia.cliente.id))
        .size
})
</script>

<template>
    <div class="min-h-screen bg-slate-50 p-6 lg:p-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <!-- Encabezado -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">
                        FITCONTROL
                    </p>

                    <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                        Asistencias
                    </h1>

                    <p class="mt-1 text-slate-500">
                        Control y registro de entradas al gimnasio.
                    </p>
                </div>

                <Link
                    href="/asistencias/create"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition duration-200 hover:-translate-y-0.5 hover:bg-blue-700"
                >
                    <CalendarCheck class="h-5 w-5" />
                    Registrar asistencia
                </Link>
            </div>

            <!-- Estadísticas -->
            <div class="grid gap-5 sm:grid-cols-3">

                <div
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Total asistencias
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ totalAsistencias }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-blue-100 p-3 text-blue-600">
                            <CalendarCheck class="h-6 w-6" />
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Asistencias hoy
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ asistenciasHoy }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-emerald-100 p-3 text-emerald-600">
                            <Clock class="h-6 w-6" />
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Clientes registrados
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ clientesUnicos }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-violet-100 p-3 text-violet-600">
                            <Users class="h-6 w-6" />
                        </div>
                    </div>
                </div>

            </div>

            <!-- Contenido -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <!-- Barra de búsqueda -->
                <div class="border-b border-slate-200 p-5">
                    <div class="relative max-w-md">
                        <Search
                            class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                        />

                        <input
                            v-model="buscar"
                            type="text"
                            placeholder="Buscar por cliente o CI..."
                            class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        />
                    </div>
                </div>

                <!-- Tabla -->
                <div class="overflow-x-auto">

                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50">
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Cliente
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    CI
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Fecha
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Hora de entrada
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            <tr
                                v-for="asistencia in asistenciasFiltradas"
                                :key="asistencia.id"
                                class="transition hover:bg-slate-50"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700"
                                        >
                                            {{ asistencia.cliente.nombre.charAt(0) }}
                                        </div>

                                        <div>
                                            <p class="font-semibold text-slate-900">
                                                {{ asistencia.cliente.nombre }}
                                                {{ asistencia.cliente.apellido }}
                                            </p>

                                            <p class="text-sm text-slate-500">
                                                Cliente #{{ asistencia.cliente.id }}
                                            </p>
                                        </div>

                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ asistencia.cliente.ci }}
                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-slate-700">
                                    {{ formatearFecha(asistencia.fecha) }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-semibold text-emerald-700"
                                    >
                                        <Clock class="h-4 w-4" />
                                        {{ formatearHora(asistencia.hora_entrada) }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Sin resultados -->
                            <tr v-if="asistenciasFiltradas.length === 0">
                                <td
                                    colspan="4"
                                    class="px-6 py-16 text-center"
                                >
                                    <div class="mx-auto flex max-w-sm flex-col items-center">

                                        <div class="rounded-full bg-slate-100 p-4">
                                            <CalendarCheck class="h-8 w-8 text-slate-400" />
                                        </div>

                                        <h3 class="mt-4 text-lg font-semibold text-slate-900">
                                            No hay asistencias
                                        </h3>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Todavía no existen registros de asistencia.
                                        </p>

                                        <Link
                                            href="/asistencias/create"
                                            class="mt-5 rounded-xl bg-blue-600 px-4 py-2.5 font-semibold text-white transition hover:bg-blue-700"
                                        >
                                            Registrar primera asistencia
                                        </Link>

                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</template>