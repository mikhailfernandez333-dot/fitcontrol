<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

interface Cliente {
    id: number
    nombre: string
    apellido: string
    ci: string
}

interface Plan {
    id: number
    nombre: string
    duracion_dias: number
    precio: string | number
}

interface Membresia {
    id: number
    cliente: Cliente
    plan: Plan
    fecha_inicio: string
    fecha_vencimiento: string
    estado: 'activa' | 'vencida' | 'cancelada'
}

const props = defineProps<{
    membresias: Membresia[]
}>()

const buscar = ref('')

const membresiasFiltradas = computed(() => {
    const texto = buscar.value.toLowerCase().trim()

    if (!texto) {
        return props.membresias
    }

    return props.membresias.filter((membresia) => {
        const cliente = `${membresia.cliente.nombre} ${membresia.cliente.apellido}`.toLowerCase()
        const ci = membresia.cliente.ci.toLowerCase()
        const plan = membresia.plan.nombre.toLowerCase()

        return (
            cliente.includes(texto) ||
            ci.includes(texto) ||
            plan.includes(texto)
        )
    })
})

const activas = computed(() =>
    props.membresias.filter((m) => m.estado === 'activa').length
)

const vencidas = computed(() =>
    props.membresias.filter((m) => m.estado === 'vencida').length
)

const obtenerEstado = (estado: string) => {
    if (estado === 'activa') {
        return {
            texto: 'Activa',
            clase: 'bg-emerald-100 text-emerald-700',
        }
    }

    if (estado === 'vencida') {
        return {
            texto: 'Vencida',
            clase: 'bg-red-100 text-red-700',
        }
    }

    return {
        texto: 'Cancelada',
        clase: 'bg-slate-100 text-slate-700',
    }
}

const formatearFecha = (fecha: string) => {
    const fechaCorta = fecha.substring(0, 10)
    const partes = fechaCorta.split('-')

    if (partes.length !== 3) {
        return fecha
    }

    return `${partes[2]}/${partes[1]}/${partes[0]}`
}

</script>

<template>
    <Head title="Membresías" />

    <div class="min-h-screen bg-slate-50">
        <!-- Encabezado -->
        <header class="border-b border-slate-200 bg-white">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5"
            >
                <div>
                    <p
                        class="text-sm font-semibold uppercase tracking-widest text-indigo-600"
                    >
                        FITCONTROL
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-slate-900">
                        Membresías
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Controla las membresías y vencimientos de tus clientes.
                    </p>
                </div>

                <Link
                    href="/membresias/create"
                    class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md"
                >
                    + Nueva membresía
                </Link>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-6 py-8">
            <!-- Estadísticas -->
            <div class="grid gap-5 md:grid-cols-3">
                <div
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <p class="text-sm font-medium text-slate-500">
                        Total membresías
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ props.membresias.length }}
                    </p>
                </div>

                <div
                    class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm"
                >
                    <p class="text-sm font-medium text-emerald-700">
                        Membresías activas
                    </p>

                    <p class="mt-2 text-3xl font-bold text-emerald-800">
                        {{ activas }}
                    </p>
                </div>

                <div
                    class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm"
                >
                    <p class="text-sm font-medium text-red-700">
                        Membresías vencidas
                    </p>

                    <p class="mt-2 text-3xl font-bold text-red-800">
                        {{ vencidas }}
                    </p>
                </div>
            </div>

            <!-- Barra de búsqueda -->
            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="relative">
                    <input
                        v-model="buscar"
                        type="text"
                        placeholder="Buscar por cliente, CI o plan..."
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-10 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    />

                    <span
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                    >
                        🔎
                    </span>
                </div>
            </div>

            <!-- Lista -->
            <div
                class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <div
                    class="border-b border-slate-200 px-6 py-5"
                >
                    <h2 class="font-bold text-slate-900">
                        Registro de membresías
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ membresiasFiltradas.length }} resultado(s)
                    </p>
                </div>

                <!-- Tabla -->
                <div
                    v-if="membresiasFiltradas.length > 0"
                    class="overflow-x-auto"
                >
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-wider text-slate-500">
                                <th class="px-6 py-4 font-semibold">
                                    Cliente
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Plan
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Inicio
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Vencimiento
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Estado
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="membresia in membresiasFiltradas"
                                :key="membresia.id"
                                class="transition hover:bg-slate-50"
                            >
                                <!-- Cliente -->
                                <td class="px-6 py-5">
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
                                                {{
                                                    membresia.cliente.nombre
                                                }}
                                                {{
                                                    membresia.cliente.apellido
                                                }}
                                            </p>

                                            <p
                                                class="text-sm text-slate-500"
                                            >
                                                CI:
                                                {{ membresia.cliente.ci }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Plan -->
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-slate-900">
                                        {{ membresia.plan.nombre }}
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        {{ membresia.plan.duracion_dias }} días
                                    </p>
                                </td>

                                <!-- Inicio -->
                                <td
                                    class="px-6 py-5 text-sm text-slate-600"
                                >
                                    {{
                                        formatearFecha(
                                            membresia.fecha_inicio
                                        )
                                    }}
                                </td>

                                <!-- Vencimiento -->
                                <td
                                    class="px-6 py-5 text-sm font-medium text-slate-700"
                                >
                                    {{
                                        formatearFecha(
                                            membresia.fecha_vencimiento
                                        )
                                    }}
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-5">
                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-bold"
                                        :class="
                                            obtenerEstado(
                                                membresia.estado
                                            ).clase
                                        "
                                    >
                                        {{
                                            obtenerEstado(
                                                membresia.estado
                                            ).texto
                                        }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Sin resultados -->
                <div
                    v-else
                    class="px-6 py-16 text-center"
                >
                    <div class="text-5xl">
                        🎫
                    </div>

                    <h3
                        class="mt-4 text-lg font-bold text-slate-900"
                    >
                        No hay membresías
                    </h3>

                    <p
                        class="mx-auto mt-2 max-w-md text-sm text-slate-500"
                    >
                        Todavía no existen membresías registradas o no
                        encontramos resultados para tu búsqueda.
                    </p>

                    <Link
                        href="/membresias/create"
                        class="mt-6 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Crear primera membresía
                    </Link>
                </div>
            </div>
        </main>
    </div>
</template>