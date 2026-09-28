<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

interface Cliente {
    id: number
    nombre: string
    apellido: string
    ci: string
    telefono: string | null
    email: string | null
    fecha_nacimiento: string | null
    foto: string | null
    activo: boolean
}

defineProps<{
    cliente: Cliente
}>()
</script>

<template>
    <Head :title="`Cliente - ${cliente.nombre}`" />

    <div class="min-h-screen bg-slate-50 p-6">
        <div class="mx-auto max-w-5xl">

            <!-- Encabezado -->
            <div class="mb-8">
                <Link
                    href="/clientes"
                    class="text-sm font-medium text-blue-600 transition hover:text-blue-800"
                >
                    ← Volver a clientes
                </Link>

                <div class="mt-5">
                    <p class="text-sm font-semibold tracking-wide text-blue-600">
                        FITCONTROL
                    </p>

                    <h1 class="mt-1 text-3xl font-bold text-slate-900">
                        Información del cliente
                    </h1>

                    <p class="mt-2 text-slate-500">
                        Consulta los datos registrados de este cliente.
                    </p>
                </div>
            </div>

            <!-- Tarjeta principal -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

                <!-- Cabecera del cliente -->
                <div class="border-b border-slate-200 bg-gradient-to-r from-blue-50 to-white px-6 py-8">
                    <div class="flex items-center gap-5">

                        <div
    class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-100 text-2xl font-bold text-blue-600"
>
    <img
        v-if="cliente.foto"
        :src="`/storage/${cliente.foto}`"
        :alt="`${cliente.nombre} ${cliente.apellido}`"
        class="h-full w-full object-cover"
    />

    <span v-else>
        {{ cliente.nombre.charAt(0).toUpperCase() }}
    </span>
</div>

                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">
                                {{ cliente.nombre }} {{ cliente.apellido }}
                            </h2>

                            <p class="mt-1 text-slate-500">
                                CI: {{ cliente.ci }}
                            </p>

                            <span
                                v-if="cliente.activo"
                                class="mt-3 inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
                            >
                                ● Cliente activo
                            </span>

                            <span
                                v-else
                                class="mt-3 inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700"
                            >
                                ● Cliente inactivo
                            </span>
                        </div>

                    </div>
                </div>

                <!-- Información -->
                <div class="p-6">

                    <h3 class="mb-5 text-lg font-semibold text-slate-900">
                        Datos personales
                    </h3>

                    <div class="grid gap-5 md:grid-cols-2">

                        <!-- Nombre -->
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">
                                Nombre completo
                            </p>

                            <p class="mt-1 font-semibold text-slate-900">
                                {{ cliente.nombre }} {{ cliente.apellido }}
                            </p>
                        </div>

                        <!-- CI -->
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">
                                Cédula de identidad
                            </p>

                            <p class="mt-1 font-semibold text-slate-900">
                                {{ cliente.ci }}
                            </p>
                        </div>

                        <!-- Teléfono -->
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">
                                Teléfono
                            </p>

                            <p class="mt-1 font-semibold text-slate-900">
                                {{ cliente.telefono || 'No registrado' }}
                            </p>
                        </div>

                        <!-- Correo -->
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">
                                Correo electrónico
                            </p>

                            <p class="mt-1 font-semibold text-slate-900">
                                {{ cliente.email || 'No registrado' }}
                            </p>
                        </div>

                        <!-- Fecha nacimiento -->
                        <div class="rounded-xl bg-slate-50 p-4 md:col-span-2">
                            <p class="text-sm text-slate-500">
                                Fecha de nacimiento
                            </p>

                            <p class="mt-1 font-semibold text-slate-900">
                                {{ cliente.fecha_nacimiento || 'No registrada' }}
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5">

                    <Link
                        href="/clientes"
                        class="rounded-xl px-5 py-3 font-semibold text-slate-600 transition hover:bg-slate-200"
                    >
                        Volver
                    </Link>

                    <Link
                        :href="`/clientes/${cliente.id}/edit`"
                        class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700"
                    >
                        Editar cliente
                    </Link>

                </div>

            </div>
        </div>
    </div>
</template>