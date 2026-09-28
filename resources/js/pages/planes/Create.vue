<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    nombre: '',
    descripcion: '',
    duracion_dias: 30,
    precio: '',
    activo: true,
})

const submit = () => {
    form.post('/planes')
}
</script>

<template>
    <Head title="Nuevo plan" />

    <div class="min-h-screen bg-slate-50 p-6">
        <div class="mx-auto max-w-3xl">

            <!-- Encabezado -->
            <div class="mb-8">
                <Link
                    href="/planes"
                    class="text-sm font-medium text-blue-600 transition hover:text-blue-800"
                >
                    ← Volver a planes
                </Link>

                <div class="mt-4">
                    <p class="text-sm font-semibold tracking-wide text-blue-600">
                        FITCONTROL
                    </p>

                    <h1 class="mt-1 text-3xl font-bold text-slate-900">
                        Crear nuevo plan
                    </h1>

                    <p class="mt-2 text-slate-500">
                        Define la duración, precio y características del plan.
                    </p>
                </div>
            </div>

            <!-- Formulario -->
            <form
                @submit.prevent="submit"
                class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200"
            >

                <!-- Cabecera -->
                <div class="border-b border-slate-200 px-6 py-5">
                    <div class="flex items-center gap-4">

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-2xl"
                        >
                            🏋️
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Información del plan
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Completa los datos para registrar el plan.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="grid gap-6 p-6">

                    <!-- Nombre -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Nombre del plan
                        </label>

                        <input
                            v-model="form.nombre"
                            type="text"
                            placeholder="Ej. Plan Mensual"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />

                        <p
                            v-if="form.errors.nombre"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.nombre }}
                        </p>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Descripción
                        </label>

                        <textarea
                            v-model="form.descripcion"
                            rows="4"
                            placeholder="Ej. Acceso completo al gimnasio durante 30 días."
                            class="w-full resize-none rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        ></textarea>

                        <p
                            v-if="form.errors.descripcion"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.descripcion }}
                        </p>
                    </div>

                    <!-- Duración y precio -->
                    <div class="grid gap-6 md:grid-cols-2">

                        <!-- Duración -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Duración
                            </label>

                            <div class="relative">
                                <input
                                    v-model="form.duracion_dias"
                                    type="number"
                                    min="1"
                                    placeholder="30"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-16 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                />

                                <span
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"
                                >
                                    días
                                </span>
                            </div>

                            <p
                                v-if="form.errors.duracion_dias"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.duracion_dias }}
                            </p>
                        </div>

                        <!-- Precio -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Precio
                            </label>

                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 font-medium text-slate-400"
                                >
                                    Bs
                                </span>

                                <input
                                    v-model="form.precio"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    placeholder="100.00"
                                    class="w-full rounded-xl border border-slate-300 py-3 pl-12 pr-4 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                />
                            </div>

                            <p
                                v-if="form.errors.precio"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.precio }}
                            </p>
                        </div>

                    </div>

                    <!-- Estado -->
                    <div>
                        <div class="rounded-xl bg-slate-50 p-4 ring-1 ring-slate-200">

                            <label class="flex cursor-pointer items-center gap-3">

                                <input
                                    v-model="form.activo"
                                    type="checkbox"
                                    class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                />

                                <div>
                                    <p class="font-medium text-slate-800">
                                        Plan activo
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        Este plan estará disponible para nuevas membresías.
                                    </p>
                                </div>

                            </label>

                        </div>
                    </div>

                </div>

                <!-- Botones -->
                <div
                    class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-5"
                >

                    <Link
                        href="/planes"
                        class="rounded-xl px-5 py-3 font-semibold text-slate-600 transition hover:bg-slate-200"
                    >
                        Cancelar
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {{ form.processing ? 'Guardando...' : 'Crear plan' }}
                    </button>

                </div>

            </form>

        </div>
    </div>
</template>