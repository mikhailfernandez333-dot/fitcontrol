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
    plan: Plan
}

interface Pago {
    id: number
    cliente: Cliente
    membresia: Membresia
    monto: string | number
    metodo_pago: 'efectivo' | 'qr' | 'tarjeta' | 'transferencia'
    fecha_pago: string
    numero_recibo: string
    observacion: string | null
}

const props = defineProps<{
    pagos: Pago[]
}>()

const buscar = ref('')

const pagosFiltrados = computed(() => {
    const texto = buscar.value.toLowerCase().trim()

    if (!texto) {
        return props.pagos
    }

    return props.pagos.filter((pago) => {
        const cliente =
            `${pago.cliente.nombre} ${pago.cliente.apellido}`.toLowerCase()

        const ci = pago.cliente.ci.toLowerCase()
        const recibo = pago.numero_recibo.toLowerCase()
        const metodo = pago.metodo_pago.toLowerCase()

        return (
            cliente.includes(texto) ||
            ci.includes(texto) ||
            recibo.includes(texto) ||
            metodo.includes(texto)
        )
    })
})

const totalPagado = computed(() => {
    return props.pagos.reduce(
        (total, pago) => total + Number(pago.monto),
        0,
    )
})

const pagosEfectivo = computed(() => {
    return props.pagos.filter(
        (pago) => pago.metodo_pago === 'efectivo',
    ).length
})

const formatearFecha = (fecha: string) => {
    const fechaCorta = fecha.substring(0, 10)
    const partes = fechaCorta.split('-')

    if (partes.length !== 3) {
        return fecha
    }

    return `${partes[2]}/${partes[1]}/${partes[0]}`
}

const nombreMetodo = (metodo: string) => {
    const metodos: Record<string, string> = {
        efectivo: 'Efectivo',
        qr: 'QR',
        tarjeta: 'Tarjeta',
        transferencia: 'Transferencia',
    }

    return metodos[metodo] ?? metodo
}

const claseMetodo = (metodo: string) => {
    const clases: Record<string, string> = {
        efectivo: 'bg-emerald-50 text-emerald-700',
        qr: 'bg-indigo-50 text-indigo-700',
        tarjeta: 'bg-amber-50 text-amber-700',
        transferencia: 'bg-blue-50 text-blue-700',
    }

    return clases[metodo] ?? 'bg-slate-100 text-slate-700'
}
</script>

<template>
    <Head title="Pagos" />

    <div class="min-h-screen bg-slate-50">
        <!-- Encabezado -->
        <header class="border-b border-slate-200 bg-white">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5"
            >
                <div>
                    <p
                        class="text-sm font-semibold uppercase tracking-widest !text-indigo-600"
                    >
                        FITCONTROL
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-slate-900">
                        Pagos
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Controla los pagos realizados por tus clientes.
                    </p>
                </div>

                <Link
                    href="/pagos/create"
                    class="rounded-xl !bg-indigo-600 px-5 py-3 text-sm font-semibold !text-white shadow-sm transition hover:!bg-indigo-700 hover:shadow-md"
                >
                    + Registrar pago
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
                        Total recaudado
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        Bs {{ totalPagado.toFixed(2) }}
                    </p>
                </div>

                <div
                    class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5 shadow-sm"
                >
                    <p class="text-sm font-medium text-indigo-700">
                        Pagos registrados
                    </p>

                    <p class="mt-2 text-3xl font-bold text-indigo-800">
                        {{ props.pagos.length }}
                    </p>
                </div>

                <div
                    class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm"
                >
                    <p class="text-sm font-medium text-emerald-700">
                        Pagos en efectivo
                    </p>

                    <p class="mt-2 text-3xl font-bold text-emerald-800">
                        {{ pagosEfectivo }}
                    </p>
                </div>
            </div>

            <!-- Buscador -->
            <div
                class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >
                <input
                    v-model="buscar"
                    type="text"
                    placeholder="Buscar por cliente, CI, recibo o método..."
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                />
            </div>

            <!-- Tabla -->
            <div
                class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="font-bold text-slate-900">
                        Historial de pagos
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ pagosFiltrados.length }} resultado(s)
                    </p>
                </div>

                <div
                    v-if="pagosFiltrados.length > 0"
                    class="overflow-x-auto"
                >
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr
                                class="text-left text-xs uppercase tracking-wider text-slate-500"
                            >
                                <th class="px-6 py-4 font-semibold">
                                    Cliente
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Plan
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Monto
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Método
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Fecha
                                </th>

                                <th class="px-6 py-4 font-semibold">
                                    Recibo
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="pago in pagosFiltrados"
                                :key="pago.id"
                                class="transition hover:bg-slate-50"
                            >
                                <!-- Cliente -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-700"
                                        >
                                            {{
                                                pago.cliente.nombre
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>

                                        <div>
                                            <p
                                                class="font-semibold text-slate-900"
                                            >
                                                {{ pago.cliente.nombre }}
                                                {{ pago.cliente.apellido }}
                                            </p>

                                            <p class="text-sm text-slate-500">
                                                CI: {{ pago.cliente.ci }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Plan -->
                                <td class="px-6 py-5">
                                    <p class="font-semibold text-slate-900">
                                        {{ pago.membresia.plan.nombre }}
                                    </p>
                                </td>

                                <!-- Monto -->
                                <td class="px-6 py-5">
                                    <p
                                        class="font-bold text-emerald-600"
                                    >
                                        Bs {{ Number(pago.monto).toFixed(2) }}
                                    </p>
                                </td>

                                <!-- Método -->
                                <td class="px-6 py-5">
                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-bold"
                                        :class="
                                            claseMetodo(
                                                pago.metodo_pago,
                                            )
                                        "
                                    >
                                        {{
                                            nombreMetodo(
                                                pago.metodo_pago,
                                            )
                                        }}
                                    </span>
                                </td>

                                <!-- Fecha -->
                                <td
                                    class="px-6 py-5 text-sm text-slate-600"
                                >
                                    {{ formatearFecha(pago.fecha_pago) }}
                                </td>

                                <!-- Recibo -->
                                <td class="px-6 py-5">
                                    <span
                                        class="rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700"
                                    >
                                        {{ pago.numero_recibo }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Sin pagos -->
                <div
                    v-else
                    class="px-6 py-16 text-center"
                >
                    <div class="text-5xl">
                        💰
                    </div>

                    <h3
                        class="mt-4 text-lg font-bold text-slate-900"
                    >
                        No hay pagos registrados
                    </h3>

                    <p
                        class="mx-auto mt-2 max-w-md text-sm text-slate-500"
                    >
                        Registra el primer pago para comenzar a controlar
                        los ingresos del gimnasio.
                    </p>

                    <Link
                        href="/pagos/create"
                        class="mt-6 inline-flex rounded-xl !bg-indigo-600 px-5 py-3 text-sm font-semibold !text-white transition hover:!bg-indigo-700"
                    >
                        Registrar primer pago
                    </Link>
                </div>
            </div>
        </main>
    </div>
</template>