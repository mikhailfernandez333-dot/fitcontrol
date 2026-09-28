<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

interface Plan {
    id: number
    nombre: string
    descripcion: string | null
    duracion_dias: number
    precio: string | number
    activo: boolean
}

const props = defineProps<{
    plan: Plan
}>()

const form = useForm({
    nombre: props.plan.nombre,
    descripcion: props.plan.descripcion ?? '',
    duracion_dias: props.plan.duracion_dias,
    precio: props.plan.precio,
    activo: props.plan.activo,
})

const actualizarPlan = () => {
    form.put(`/planes/${props.plan.id}`)
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 px-6 py-10">
        <div class="mx-auto max-w-3xl">

            <!-- Encabezado -->
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">
                    FITCONTROL
                </p>

                <div class="mt-2 flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">
                            Editar plan
                        </h1>

                        <p class="mt-2 text-slate-500">
                            Modifica la información del plan seleccionado.
                        </p>
                    </div>

                    <a
                        href="/planes"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                    >
                        ← Volver
                    </a>
                </div>
            </div>

            <!-- Formulario -->
            <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">

                <form @submit.prevent="actualizarPlan" class="space-y-6">

                    <!-- Nombre -->
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Nombre del plan
                        </label>

                        <input
                            v-model="form.nombre"
                            type="text"
                            placeholder="Ej. Mensual"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
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
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Descripción
                        </label>

                        <textarea
                            v-model="form.descripcion"
                            rows="4"
                            placeholder="Describe qué incluye este plan..."
                            class="w-full resize-none rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
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
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Duración (días)
                            </label>

                            <input
                                v-model="form.duracion_dias"
                                type="number"
                                min="1"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            />

                            <p
                                v-if="form.errors.duracion_dias"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.duracion_dias }}
                            </p>
                        </div>

                        <!-- Precio -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Precio (Bs)
                            </label>

                            <input
                                v-model="form.precio"
                                type="number"
                                min="0"
                                step="0.01"
                                placeholder="100.00"
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            />

                            <p
                                v-if="form.errors.precio"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.precio }}
                            </p>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <label class="flex cursor-pointer items-center gap-3">
                            <input
                                v-model="form.activo"
                                type="checkbox"
                                class="h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            />

                            <div>
                                <p class="font-semibold text-slate-800">
                                    Plan activo
                                </p>

                                <p class="text-sm text-slate-500">
                                    Permite utilizar este plan para nuevas membresías.
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">

                        <a
                            href="/planes"
                            class="rounded-xl border border-slate-200 bg-white px-6 py-3 font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Cancelar
                        </a>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
                        </button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</template>