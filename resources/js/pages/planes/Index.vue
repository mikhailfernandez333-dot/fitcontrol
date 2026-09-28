<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

interface Plan {
    id: number
    nombre: string
    descripcion: string | null
    duracion_dias: number
    precio: string | number
    activo: boolean
}

const props = defineProps<{
    planes: Plan[]
}>()

const planAEliminar = ref<Plan | null>(null)

const abrirModalEliminar = (plan: Plan) => {
    planAEliminar.value = plan
}

const cancelarEliminar = () => {
    planAEliminar.value = null
}

const confirmarEliminar = () => {
    if (!planAEliminar.value) return

    const form = useForm({})

    form.delete(`/planes/${planAEliminar.value.id}`, {
        onSuccess: () => {
            planAEliminar.value = null
        },
    })
}

</script>

<template>
    <Head title="Planes" />

    <div class="min-h-screen bg-slate-50 p-6">
        <div class="mx-auto max-w-6xl">

            <!-- Encabezado -->
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

                <div>
                    <p class="text-sm font-semibold tracking-wide text-blue-600">
                        FITCONTROL
                    </p>

                    <h1 class="mt-1 text-3xl font-bold text-slate-900">
                        Planes
                    </h1>

                    <p class="mt-2 text-slate-500">
                        Administra los planes y precios disponibles para los clientes.
                    </p>
                </div>

                <Link
    href="/planes/create"
    class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700"
>
    + Nuevo plan
</Link>

            </div>

            <!-- Resumen -->
            <div class="mb-8 grid gap-4 md:grid-cols-3">

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">
                        Total de planes
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ props.planes.length }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">
                        Planes activos
                    </p>

                    <p class="mt-2 text-3xl font-bold text-emerald-600">
                        {{ props.planes.filter(plan => plan.activo).length }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-sm font-medium text-slate-500">
                        Planes inactivos
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-400">
                        {{ props.planes.filter(plan => !plan.activo).length }}
                    </p>
                </div>

            </div>

            <!-- Lista de planes -->
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        Planes disponibles
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Consulta la duración, precio y estado de cada plan.
                    </p>
                </div>

                <!-- Hay planes -->
                <div
                    v-if="props.planes.length > 0"
                    class="grid gap-5 p-6 md:grid-cols-2 lg:grid-cols-3"
                >

                    <div
                        v-for="plan in props.planes"
                        :key="plan.id"
                        class="group rounded-2xl border border-slate-200 bg-white p-6 transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                    >

                        <!-- Encabezado de tarjeta -->
                        <div class="flex items-start justify-between gap-4">

                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-2xl">
                                🏋️
                            </div>

                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold"
                                :class="
                                    plan.activo
                                        ? 'bg-emerald-50 text-emerald-700'
                                        : 'bg-slate-100 text-slate-500'
                                "
                            >
                                {{ plan.activo ? 'Activo' : 'Inactivo' }}
                            </span>

                        </div>

                        <!-- Información -->
                        <div class="mt-5">

                            <h3 class="text-xl font-bold text-slate-900">
                                {{ plan.nombre }}
                            </h3>

                            <p class="mt-2 min-h-10 text-sm text-slate-500">
                                {{ plan.descripcion || 'Sin descripción disponible.' }}
                            </p>

                        </div>

                        <!-- Precio -->
                        <div class="mt-6 rounded-xl bg-slate-50 p-4">

                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                Precio
                            </p>

                            <p class="mt-1 text-3xl font-bold text-slate-900">
                                Bs {{ Number(plan.precio).toFixed(2) }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ plan.duracion_dias }} días de duración
                            </p>

                        </div>

                        <!-- Acciones -->
                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">

                            <span class="text-sm text-slate-400">
                                Plan #{{ plan.id }}
                            </span>

                            <div class="flex gap-3">

                                <a
    :href="`/planes/${plan.id}/edit`"
    class="text-sm font-semibold text-amber-600 transition hover:text-amber-800"
>
    Editar
</a>

                              <button
    type="button"
    class="text-sm font-semibold text-red-600 transition hover:text-red-800"
    @click="abrirModalEliminar(plan)"
>
    Eliminar
</button>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Sin planes -->
                <div
                    v-else
                    class="px-6 py-16 text-center"
                >
                    <div class="text-5xl">
                        🏋️
                    </div>

                    <h3 class="mt-4 text-lg font-semibold text-slate-900">
                        No hay planes registrados
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Crea tu primer plan para comenzar a gestionar las membresías.
                    </p>
                </div>

            </div>

        </div>
    </div>

<!-- Modal eliminar -->
<Transition name="modal">
    <div
        v-if="planAEliminar"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm"
        @click.self="cancelarEliminar"
    >
        <div
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
        >
            <!-- Icono -->
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                <svg
                    class="h-7 w-7 text-red-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.1 12.28A2 2 0 004.92 19h14.16a2 2 0 001.73-2.86l-7.1-12.28a2 2 0 00-3.46 0z"
                    />
                </svg>
            </div>

            <!-- Texto -->
            <div class="mt-5 text-center">
                <h2 class="text-xl font-bold text-slate-900">
                    ¿Eliminar este plan?
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Estás a punto de eliminar el plan
                    <span class="font-semibold text-slate-800">
                        "{{ planAEliminar.nombre }}"
                    </span>.
                    Esta acción no se puede deshacer.
                </p>
            </div>

            <!-- Botones -->
            <div class="mt-6 flex gap-3">
                <button
                    type="button"
                    class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 transition hover:bg-slate-50"
                    @click="cancelarEliminar"
                >
                    Cancelar
                </button>

                <button
                    type="button"
                    class="flex-1 rounded-xl bg-red-600 px-4 py-3 font-semibold text-white transition hover:bg-red-700"
                    @click="confirmarEliminar"
                >
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</Transition>



</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>