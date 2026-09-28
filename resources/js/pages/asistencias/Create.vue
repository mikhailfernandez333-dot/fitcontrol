<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'
import {
    ArrowLeft,
    CalendarCheck,
    Clock,
    User,
} from '@lucide/vue'

interface Cliente {
    id: number
    nombre: string
    apellido: string
    ci: string
}

const props = defineProps<{
    clientes: Cliente[]
}>()

const ahora = new Date()

const fechaHoy = `${ahora.getFullYear()}-${String(
    ahora.getMonth() + 1,
).padStart(2, '0')}-${String(ahora.getDate()).padStart(2, '0')}`

const horaActual = `${String(ahora.getHours()).padStart(2, '0')}:${String(
    ahora.getMinutes(),
).padStart(2, '0')}`

const form = useForm({
    cliente_id: '',
    fecha: fechaHoy,
    hora_entrada: horaActual,
})

const guardar = () => {
    form.post('/asistencias')
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 p-6 lg:p-8">
        <div class="mx-auto max-w-3xl">

            <!-- Encabezado -->
            <div class="mb-8">
                <Link
                    href="/asistencias"
                    class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-blue-600"
                >
                    <ArrowLeft class="h-4 w-4" />
                    Volver a asistencias
                </Link>

                <p class="text-sm font-semibold uppercase tracking-wider text-blue-600">
                    FITCONTROL
                </p>

                <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">
                    Registrar asistencia
                </h1>

                <p class="mt-1 text-slate-500">
                    Registra la entrada de un cliente al gimnasio.
                </p>
            </div>

            <!-- Formulario -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">

                <div class="mb-8 flex items-center gap-4 rounded-2xl bg-blue-50 p-5">
                    <div class="rounded-xl bg-blue-100 p-3 text-blue-600">
                        <CalendarCheck class="h-7 w-7" />
                    </div>

                    <div>
                        <h2 class="font-bold text-slate-900">
                            Nueva asistencia
                        </h2>

                        <p class="text-sm text-slate-500">
                            Completa los datos de ingreso.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="guardar" class="space-y-6">

                    <!-- Cliente -->
                    <div>
                        <label
                            for="cliente_id"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Cliente
                        </label>

                        <div class="relative">
                            <User
                                class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                            />

                            <select
                                id="cliente_id"
                                v-model="form.cliente_id"
                                class="w-full appearance-none rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            >
                                <option value="" disabled>
                                    Selecciona un cliente
                                </option>

                                <option
                                    v-for="cliente in props.clientes"
                                    :key="cliente.id"
                                    :value="cliente.id"
                                >
                                    {{ cliente.nombre }}
                                    {{ cliente.apellido }}
                                    — CI: {{ cliente.ci }}
                                </option>
                            </select>
                        </div>

                        <p
                            v-if="form.errors.cliente_id"
                            class="mt-2 text-sm font-medium text-red-600"
                        >
                            {{ form.errors.cliente_id }}
                        </p>
                    </div>

                    <!-- Fecha y hora -->
                    <div class="grid gap-6 sm:grid-cols-2">

                        <!-- Fecha -->
                        <div>
                            <label
                                for="fecha"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Fecha
                            </label>

                            <div class="relative">
                                <CalendarCheck
                                    class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                />

                                <input
                                    id="fecha"
                                    v-model="form.fecha"
                                    type="date"
                                    class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                />
                            </div>

                            <p
                                v-if="form.errors.fecha"
                                class="mt-2 text-sm font-medium text-red-600"
                            >
                                {{ form.errors.fecha }}
                            </p>
                        </div>

                        <!-- Hora -->
                        <div>
                            <label
                                for="hora_entrada"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Hora de entrada
                            </label>

                            <div class="relative">
                                <Clock
                                    class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                                />

                                <input
                                    id="hora_entrada"
                                    v-model="form.hora_entrada"
                                    type="time"
                                    class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                />
                            </div>

                            <p
                                v-if="form.errors.hora_entrada"
                                class="mt-2 text-sm font-medium text-red-600"
                            >
                                {{ form.errors.hora_entrada }}
                            </p>
                        </div>

                    </div>

                    <!-- Información -->
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex gap-3">
                            <Clock class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" />

                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    Registro de entrada
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    La fecha y hora se completan automáticamente con
                                    los datos actuales, pero puedes modificarlos.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">

                        <Link
                            href="/asistencias"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Cancelar
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <CalendarCheck class="h-5 w-5" />

                            {{
                                form.processing
                                    ? 'Guardando...'
                                    : 'Registrar asistencia'
                            }}
                        </button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</template>