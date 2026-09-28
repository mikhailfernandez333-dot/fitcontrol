<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'

interface Cliente {
    id: number
    nombre: string
    apellido: string
    ci: string
}

interface Plan {
    id: number
    nombre: string
    descripcion: string | null
    duracion_dias: number
    precio: string | number
}

const props = defineProps<{
    clientes: Cliente[]
    planes: Plan[]
}>()

const form = useForm({
    cliente_id: '',
    plan_id: '',
    fecha_inicio: '',
    fecha_vencimiento: '',
    estado: 'activa',
})

const planSeleccionado = computed(() => {
    return props.planes.find(
        (plan) => String(plan.id) === String(form.plan_id)
    )
})

const calcularVencimiento = () => {
    if (!form.fecha_inicio || !planSeleccionado.value) {
        return
    }

    const fecha = new Date(`${form.fecha_inicio}T00:00:00`)

    fecha.setDate(
        fecha.getDate() + Number(planSeleccionado.value.duracion_dias)
    )

    const year = fecha.getFullYear()
    const month = String(fecha.getMonth() + 1).padStart(2, '0')
    const day = String(fecha.getDate()).padStart(2, '0')

    form.fecha_vencimiento = `${year}-${month}-${day}`
}

const enviar = () => {
    form.post('/membresias', {
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <Head title="Nueva membresía" />

    <div class="min-h-screen bg-slate-50">
        <!-- Encabezado -->
        <header class="border-b border-slate-200 bg-white">
            <div
                class="mx-auto flex max-w-4xl items-center justify-between px-6 py-5"
            >
                <div>
                    <p
                        class="text-sm font-semibold uppercase tracking-widest text-indigo-600"
                    >
                        FITCONTROL
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-slate-900">
                        Nueva membresía
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Asigna un plan a un cliente.
                    </p>
                </div>

                <Link
                    href="/membresias"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    ← Volver
                </Link>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-6 py-8">
            <form
                @submit.prevent="enviar"
                class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8"
            >
                <!-- Cliente -->
                <div>
                    <label
                        for="cliente_id"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Cliente
                    </label>

                    <select
                        id="cliente_id"
                        v-model="form.cliente_id"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >
                        <option value="">
                            Selecciona un cliente
                        </option>

                        <option
                            v-for="cliente in props.clientes"
                            :key="cliente.id"
                            :value="cliente.id"
                        >
                            {{ cliente.nombre }}
                            {{ cliente.apellido }}
                            — CI {{ cliente.ci }}
                        </option>
                    </select>

                    <p
                        v-if="form.errors.cliente_id"
                        class="mt-2 text-sm text-red-600"
                    >
                        {{ form.errors.cliente_id }}
                    </p>
                </div>

                <!-- Plan -->
                <div class="mt-6">
                    <label
                        for="plan_id"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Plan
                    </label>

                    <select
                        id="plan_id"
                        v-model="form.plan_id"
                        @change="calcularVencimiento"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >
                        <option value="">
                            Selecciona un plan
                        </option>

                        <option
                            v-for="plan in props.planes"
                            :key="plan.id"
                            :value="plan.id"
                        >
                            {{ plan.nombre }} —
                            {{ plan.duracion_dias }} días —
                            Bs {{ Number(plan.precio).toFixed(2) }}
                        </option>
                    </select>

                    <div
                        v-if="planSeleccionado"
                        class="mt-3 rounded-xl bg-indigo-50 p-4"
                    >
                        <p class="font-semibold text-indigo-900">
                            {{ planSeleccionado.nombre }}
                        </p>

                        <p
                            v-if="planSeleccionado.descripcion"
                            class="mt-1 text-sm text-indigo-700"
                        >
                            {{ planSeleccionado.descripcion }}
                        </p>

                        <p class="mt-2 text-sm font-medium text-indigo-700">
                            Duración:
                            {{ planSeleccionado.duracion_dias }} días
                            · Bs {{ Number(planSeleccionado.precio).toFixed(2) }}
                        </p>
                    </div>

                    <p
                        v-if="form.errors.plan_id"
                        class="mt-2 text-sm text-red-600"
                    >
                        {{ form.errors.plan_id }}
                    </p>
                </div>

                <!-- Fechas -->
                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div>
                        <label
                            for="fecha_inicio"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Fecha de inicio
                        </label>

                        <input
                            id="fecha_inicio"
                            v-model="form.fecha_inicio"
                            @change="calcularVencimiento"
                            type="date"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        />

                        <p
                            v-if="form.errors.fecha_inicio"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.fecha_inicio }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="fecha_vencimiento"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Fecha de vencimiento
                        </label>

                        <input
                            id="fecha_vencimiento"
                            v-model="form.fecha_vencimiento"
                            type="date"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        />

                        <p class="mt-2 text-xs text-slate-500">
                            Se calcula automáticamente según la duración del
                            plan.
                        </p>

                        <p
                            v-if="form.errors.fecha_vencimiento"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.fecha_vencimiento }}
                        </p>
                    </div>
                </div>

                <!-- Estado -->
                <div class="mt-6">
                    <label
                        for="estado"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Estado
                    </label>

                    <select
                        id="estado"
                        v-model="form.estado"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >
                        <option value="activa">
                            Activa
                        </option>

                        <option value="vencida">
                            Vencida
                        </option>

                        <option value="cancelada">
                            Cancelada
                        </option>
                    </select>

                    <p
                        v-if="form.errors.estado"
                        class="mt-2 text-sm text-red-600"
                    >
                        {{ form.errors.estado }}
                    </p>
                </div>

                <!-- Resumen -->
                <div
                    v-if="planSeleccionado"
                    class="mt-8 rounded-2xl border border-indigo-100 bg-indigo-50 p-5"
                >
                    <p
                        class="text-xs font-bold uppercase tracking-wider text-indigo-600"
                    >
                        Resumen
                    </p>

                    <div class="mt-3 grid gap-3 sm:grid-cols-3">
                        <div>
                            <p class="text-xs text-indigo-600">
                                Plan
                            </p>

                            <p class="font-bold text-indigo-900">
                                {{ planSeleccionado.nombre }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-indigo-600">
                                Precio
                            </p>

                            <p class="font-bold text-indigo-900">
                                Bs
                                {{ Number(planSeleccionado.precio).toFixed(2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-indigo-600">
                                Duración
                            </p>

                            <p class="font-bold text-indigo-900">
                                {{ planSeleccionado.duracion_dias }} días
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div
                    class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end"
                >
                    <Link
                        href="/membresias"
                        class="rounded-xl border border-slate-300 px-6 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        Cancelar
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                       class="!bg-indigo-600 !text-white rounded-xl px-6 py-3 text-sm font-semibold shadow-sm transition hover:!bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{
                            form.processing
                                ? 'Guardando...'
                                : 'Crear membresía'
                        }}
                    </button>
                </div>
            </form>
        </main>
    </div>
</template>