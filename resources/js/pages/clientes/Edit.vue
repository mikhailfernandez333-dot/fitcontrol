<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'

interface Cliente {
    id: number
    nombre: string
    apellido: string
    ci: string
    telefono: string | null
    email: string | null
    fecha_nacimiento: string | null
    activo: boolean
}

const props = defineProps<{
    cliente: Cliente
}>()

const form = useForm({
    nombre: props.cliente.nombre,
    apellido: props.cliente.apellido,
    ci: props.cliente.ci,
    telefono: props.cliente.telefono ?? '',
    email: props.cliente.email ?? '',
    fecha_nacimiento: props.cliente.fecha_nacimiento ?? '',
    activo: props.cliente.activo,
})

const submit = () => {
    form.put(`/clientes/${props.cliente.id}`)
}
</script>

<template>
    <Head :title="`Editar - ${cliente.nombre}`" />

    <div class="min-h-screen bg-slate-50 p-6">
        <div class="mx-auto max-w-4xl">

            <!-- Encabezado -->
            <div class="mb-8">
                <Link
                    href="/clientes"
                    class="text-sm font-medium text-blue-600 transition hover:text-blue-800"
                >
                    ← Volver a clientes
                </Link>

                <div class="mt-4">
                    <p class="text-sm font-semibold tracking-wide text-blue-600">
                        FITCONTROL
                    </p>

                    <h1 class="mt-1 text-3xl font-bold text-slate-900">
                        Editar cliente
                    </h1>

                    <p class="mt-2 text-slate-500">
                        Modifica la información registrada del cliente.
                    </p>
                </div>
            </div>

            <!-- Formulario -->
            <form
                @submit.prevent="submit"
                class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200"
            >

                <div class="border-b border-slate-200 px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600"
                        >
                            {{ cliente.nombre.charAt(0).toUpperCase() }}
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                {{ cliente.nombre }} {{ cliente.apellido }}
                            </h2>

                            <p class="text-sm text-slate-500">
                                Editando información del cliente
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 p-6 md:grid-cols-2">

                    <!-- Nombre -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Nombre
                        </label>

                        <input
                            v-model="form.nombre"
                            type="text"
                            placeholder="Ej. Juan"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />

                        <p
                            v-if="form.errors.nombre"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.nombre }}
                        </p>
                    </div>

                    <!-- Apellido -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Apellido
                        </label>

                        <input
                            v-model="form.apellido"
                            type="text"
                            placeholder="Ej. Pérez"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />

                        <p
                            v-if="form.errors.apellido"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.apellido }}
                        </p>
                    </div>

                    <!-- CI -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            CI
                        </label>

                        <input
                            v-model="form.ci"
                            type="text"
                            placeholder="Ej. 12345678"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />

                        <p
                            v-if="form.errors.ci"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.ci }}
                        </p>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Teléfono
                        </label>

                        <input
                            v-model="form.telefono"
                            type="text"
                            placeholder="Ej. 70000000"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />

                        <p
                            v-if="form.errors.telefono"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.telefono }}
                        </p>
                    </div>

                    <!-- Correo -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Correo electrónico
                        </label>

                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="Ej. juan@gmail.com"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />

                        <p
                            v-if="form.errors.email"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Fecha de nacimiento -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Fecha de nacimiento
                        </label>

                        <input
                            v-model="form.fecha_nacimiento"
                            type="date"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />

                        <p
                            v-if="form.errors.fecha_nacimiento"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.fecha_nacimiento }}
                        </p>
                    </div>

                    <!-- Estado -->
                    <div class="md:col-span-2">
                        <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">
                            <label class="flex cursor-pointer items-center gap-3">
                                <input
                                    v-model="form.activo"
                                    type="checkbox"
                                    class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                />

                                <div>
                                    <p class="font-medium text-slate-800">
                                        Cliente activo
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        El cliente podrá utilizar los servicios del gimnasio.
                                    </p>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5">

                    <Link
                        href="/clientes"
                        class="rounded-xl px-5 py-3 font-semibold text-slate-600 transition hover:bg-slate-200"
                    >
                        Cancelar
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
                    </button>

                </div>
            </form>
        </div>
    </div>
</template>