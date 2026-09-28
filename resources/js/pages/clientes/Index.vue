<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface Cliente {
    id: number
    nombre: string
    apellido: string
    ci: string
    telefono: string | null
    email: string | null
    foto: string | null
    activo: boolean
}



const props = defineProps<{
    clientes: Cliente[]
}>()





const buscar = ref('')

const clientesFiltrados = computed(() => {
    const texto = buscar.value.toLowerCase().trim()

    if (!texto) {
        return props.clientes
    }

    return props.clientes.filter((cliente) =>
        `${cliente.nombre} ${cliente.apellido} ${cliente.ci} ${cliente.telefono ?? ''}`
            .toLowerCase()
            .includes(texto)
    )
})




const deleteForm = useForm({})

const clienteAEliminar = ref<Cliente | null>(null)

const abrirModalEliminar = (cliente: Cliente) => {
    clienteAEliminar.value = cliente
}

const cerrarModalEliminar = () => {
    clienteAEliminar.value = null
}

const eliminarCliente = () => {
    if (!clienteAEliminar.value) return

    deleteForm.delete(`/clientes/${clienteAEliminar.value.id}`, {
        onSuccess: () => {
            clienteAEliminar.value = null
        },
    })
}
</script>

<template>
    <Head title="Clientes" />

    <div class="min-h-screen bg-slate-50 p-6">

        <!-- Encabezado -->
        <div class="mb-8 flex items-center justify-between">

            <div>
                <p class="text-sm font-medium text-blue-600">
                    FITCONTROL
                </p>

                <h1 class="mt-1 text-3xl font-bold text-slate-900">
                    Clientes
                </h1>

                <p class="mt-1 text-slate-500">
                    Gestiona los clientes registrados en el gimnasio.
                </p>
            </div>

            <Link
                href="/clientes/create"
                class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition duration-200 hover:-translate-y-0.5 hover:bg-blue-700"
            >
                + Nuevo cliente
            </Link>

        </div>

        <!-- Estadísticas -->
        <div class="mb-6 grid gap-4 md:grid-cols-3">

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-md">
                <p class="text-sm text-slate-500">
                    Total clientes
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    {{ clientes.length }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-md">
                <p class="text-sm text-slate-500">
                    Clientes activos
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-600">
                    {{ clientes.filter(cliente => cliente.activo).length }}
                </p>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-md">
                <p class="text-sm text-slate-500">
                    Clientes inactivos
                </p>

                <p class="mt-2 text-3xl font-bold text-red-500">
                    {{ clientes.filter(cliente => !cliente.activo).length }}
                </p>
            </div>

        </div>

        <!-- Tabla -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

            <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between">
    <div>
        <h2 class="font-semibold text-slate-900">
            Lista de clientes
        </h2>

        <p class="text-sm text-slate-500">
            Busca clientes por nombre, CI o teléfono.
        </p>
    </div>

    <div class="relative w-full md:w-80">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
            🔎
        </span>

        <input
            v-model="buscar"
            type="text"
            placeholder="Buscar cliente..."
            class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-11 pr-4 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
        />
    </div>
</div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-50">
                        <tr class="text-left text-sm text-slate-500">

                            <th class="px-6 py-4 font-medium">
                                Cliente
                            </th>

                            <th class="px-6 py-4 font-medium">
                                CI
                            </th>

                            <th class="px-6 py-4 font-medium">
                                Teléfono
                            </th>

                            <th class="px-6 py-4 font-medium">
                                Estado
                            </th>

                            <th class="px-6 py-4 text-right font-medium">
                                Acción
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        <tr
                            v-for="cliente in clientesFiltrados"
                            :key="cliente.id"
                            class="transition hover:bg-slate-50"
                        >

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-600">
                                        <img
                v-if="cliente.foto"
                :src="`/storage/${cliente.foto}`"
                :alt="`${cliente.nombre} ${cliente.apellido}`"
                class="h-full w-full object-cover"
            />

            <span v-else>
                {{ cliente.nombre.charAt(0) }}
            </span>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ cliente.nombre }} {{ cliente.apellido }}
                                        </p>

                                        <p class="text-sm text-slate-500">
                                            {{ cliente.email || 'Sin correo' }}
                                        </p>
                                    </div>

                                </div>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ cliente.ci }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ cliente.telefono || 'Sin teléfono' }}
                            </td>

                            <td class="px-6 py-4">

                                <span
                                    v-if="cliente.activo"
                                    class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700"
                                >
                                    Activo
                                </span>

                                <span
                                    v-else
                                    class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700"
                                >
                                    Inactivo
                                </span>

                            </td>

                           <td class="px-6 py-4">
    <div class="flex items-center justify-end gap-4">
        <Link
            :href="`/clientes/${cliente.id}`"
            class="text-sm font-semibold text-blue-600 transition hover:text-blue-800"
        >
            Ver →
        </Link>

        <Link
            :href="`/clientes/${cliente.id}/edit`"
            class="text-sm font-semibold text-amber-600 transition hover:text-amber-800"
        >
            Editar
        </Link>

        <button
    type="button"
    @click="abrirModalEliminar(cliente)"
    class="text-sm font-semibold text-red-600 transition hover:text-red-800"
>
    Eliminar
</button>
    </div>
</td>

                        </tr>

                        <tr v-if="clientesFiltrados.length === 0">

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center"
                            >

                                <div class="text-4xl">
                                    👥
                                </div>

                                <p class="mt-3 font-semibold text-slate-900">
                                    No hay clientes todavía
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Registra tu primer cliente para comenzar.
                                </p>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Modal de confirmación -->
<Transition name="fade">
    <div
        v-if="clienteAEliminar"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm"
        @click.self="cerrarModalEliminar"
    >
        <div
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
        >
            <!-- Icono -->
            <div class="flex justify-center">
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-2xl"
                >
                    ⚠️
                </div>
            </div>

            <!-- Texto -->
            <div class="mt-5 text-center">
                <h2 class="text-xl font-bold text-slate-900">
                    Eliminar cliente
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    ¿Estás seguro de que deseas eliminar a
                    <span class="font-semibold text-slate-800">
                        {{ clienteAEliminar.nombre }}
                        {{ clienteAEliminar.apellido }}
                    </span>?
                </p>

                <p class="mt-2 text-xs text-red-500">
                    Esta acción no se puede deshacer.
                </p>
            </div>

            <!-- Botones -->
            <div class="mt-6 flex gap-3">
                <button
                    type="button"
                    @click="cerrarModalEliminar"
                    class="flex-1 rounded-xl border border-slate-300 px-4 py-3 font-semibold text-slate-700 transition hover:bg-slate-100"
                >
                    Cancelar
                </button>

                <button
                    type="button"
                    @click="eliminarCliente"
                    :disabled="deleteForm.processing"
                    class="flex-1 rounded-xl bg-red-600 px-4 py-3 font-semibold text-white shadow-lg shadow-red-600/20 transition hover:-translate-y-0.5 hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{ deleteForm.processing ? 'Eliminando...' : 'Eliminar cliente' }}
                </button>
            </div>
        </div>
    </div>
</Transition>

</template>