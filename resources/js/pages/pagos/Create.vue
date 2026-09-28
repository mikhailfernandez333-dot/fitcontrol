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
    precio: string | number
    duracion_dias: number
}

interface Membresia {
    id: number
    cliente: Cliente
    plan: Plan
    fecha_inicio: string
    fecha_vencimiento: string
}

const props = defineProps<{
    membresias: Membresia[]
}>()

const form = useForm({
    cliente_id: '',
    membresia_id: '',
    monto: '',
    metodo_pago: 'efectivo',
    fecha_pago: new Date().toISOString().substring(0, 10),
    numero_recibo: '',
    observacion: '',
})

const membresiaSeleccionada = computed(() => {
    return props.membresias.find(
        (membresia) =>
            String(membresia.id) === String(form.membresia_id),
    )
})

const seleccionarMembresia = () => {
    const membresia = membresiaSeleccionada.value

    if (!membresia) {
        form.cliente_id = ''
        form.monto = ''
        return
    }

    form.cliente_id = String(membresia.cliente.id)
    form.monto = String(membresia.plan.precio)
}

const generarRecibo = () => {
    const numero = Date.now()

    form.numero_recibo = `REC-${numero}`
}

const enviar = () => {
    form.post('/pagos', {
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <Head title="Registrar pago" />

    <div class="min-h-screen bg-slate-50">
        <!-- Encabezado -->
        <header class="border-b border-slate-200 bg-white">
            <div
                class="mx-auto flex max-w-4xl items-center justify-between px-6 py-5"
            >
                <div>
                    <p
                        class="text-sm font-semibold uppercase tracking-widest !text-indigo-600"
                    >
                        FITCONTROL
                    </p>

                    <h1 class="mt-1 text-2xl font-bold text-slate-900">
                        Registrar pago
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Registra el pago de una membresía activa.
                    </p>
                </div>

                <Link
                    href="/pagos"
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
                <!-- Membresía -->
                <div>
                    <label
                        for="membresia_id"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Membresía
                    </label>

                    <select
                        id="membresia_id"
                        v-model="form.membresia_id"
                        @change="seleccionarMembresia"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >
                        <option value="">
                            Selecciona una membresía
                        </option>

                        <option
                            v-for="membresia in props.membresias"
                            :key="membresia.id"
                            :value="membresia.id"
                        >
                            {{ membresia.cliente.nombre }}
                            {{ membresia.cliente.apellido }}
                            — {{ membresia.plan.nombre }}
                        </option>
                    </select>

                    <p
                        v-if="form.errors.membresia_id"
                        class="mt-2 text-sm text-red-600"
                    >
                        {{ form.errors.membresia_id }}
                    </p>
                </div>

                <!-- Información de membresía -->
                <div
                    v-if="membresiaSeleccionada"
                    class="mt-6 rounded-2xl border border-indigo-100 bg-indigo-50 p-5"
                >
                    <p
                        class="text-xs font-bold uppercase tracking-wider text-indigo-600"
                    >
                        Información de la membresía
                    </p>

                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        <div>
                            <p class="text-xs text-indigo-600">
                                Cliente
                            </p>

                            <p class="font-bold text-indigo-900">
                                {{ membresiaSeleccionada.cliente.nombre }}
                                {{ membresiaSeleccionada.cliente.apellido }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-indigo-600">
                                Plan
                            </p>

                            <p class="font-bold text-indigo-900">
                                {{ membresiaSeleccionada.plan.nombre }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-indigo-600">
                                Vencimiento
                            </p>

                            <p class="font-bold text-indigo-900">
                                {{
                                    membresiaSeleccionada.fecha_vencimiento.substring(
                                        0,
                                        10,
                                    )
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Monto y método -->
                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div>
                        <label
                            for="monto"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Monto
                        </label>

                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2 font-semibold text-slate-500"
                            >
                                Bs
                            </span>

                            <input
                                id="monto"
                                v-model="form.monto"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-12 pr-4 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                                placeholder="0.00"
                            />
                        </div>

                        <p
                            v-if="form.errors.monto"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.monto }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="metodo_pago"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Método de pago
                        </label>

                        <select
                            id="metodo_pago"
                            v-model="form.metodo_pago"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >
                            <option value="efectivo">
                                💵 Efectivo
                            </option>

                            <option value="qr">
                                📱 QR
                            </option>

                            <option value="tarjeta">
                                💳 Tarjeta
                            </option>

                            <option value="transferencia">
                                🏦 Transferencia
                            </option>
                        </select>

                        <p
                            v-if="form.errors.metodo_pago"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.metodo_pago }}
                        </p>
                    </div>
                </div>

                <!-- Fecha y recibo -->
                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div>
                        <label
                            for="fecha_pago"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Fecha del pago
                        </label>

                        <input
                            id="fecha_pago"
                            v-model="form.fecha_pago"
                            type="date"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        />

                        <p
                            v-if="form.errors.fecha_pago"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.fecha_pago }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="numero_recibo"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Número de recibo
                        </label>

                        <div class="flex gap-2">
                            <input
                                id="numero_recibo"
                                v-model="form.numero_recibo"
                                type="text"
                                placeholder="REC-0001"
                                class="min-w-0 flex-1 rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            />

                            <button
                                type="button"
                                @click="generarRecibo"
                                class="rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-100"
                            >
                                Generar
                            </button>
                        </div>

                        <p
                            v-if="form.errors.numero_recibo"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.numero_recibo }}
                        </p>
                    </div>
                </div>

                <!-- Observación -->
                <div class="mt-6">
                    <label
                        for="observacion"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Observación
                    </label>

                    <textarea
                        id="observacion"
                        v-model="form.observacion"
                        rows="4"
                        placeholder="Ej.: Pago correspondiente al mes de octubre."
                        class="w-full resize-none rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    ></textarea>

                    <p
                        v-if="form.errors.observacion"
                        class="mt-2 text-sm text-red-600"
                    >
                        {{ form.errors.observacion }}
                    </p>
                </div>

                <!-- Resumen -->
                <div
                    v-if="membresiaSeleccionada"
                    class="mt-8 rounded-2xl bg-slate-50 p-5"
                >
                    <p
                        class="text-xs font-bold uppercase tracking-wider text-slate-500"
                    >
                        Resumen del pago
                    </p>

                    <div
                        class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div>
                            <p class="font-semibold text-slate-900">
                                {{ membresiaSeleccionada.plan.nombre }}
                            </p>

                            <p class="text-sm text-slate-500">
                                {{
                                    membresiaSeleccionada.cliente.nombre
                                }}
                                {{
                                    membresiaSeleccionada.cliente.apellido
                                }}
                            </p>
                        </div>

                        <p class="text-2xl font-bold text-emerald-600">
                            Bs {{ Number(form.monto || 0).toFixed(2) }}
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div
                    class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end"
                >
                    <Link
                        href="/pagos"
                        class="rounded-xl border border-slate-300 px-6 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        Cancelar
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-xl !bg-indigo-600 px-6 py-3 text-sm font-semibold !text-white shadow-sm transition hover:!bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        {{
                            form.processing
                                ? 'Guardando...'
                                : 'Registrar pago'
                        }}
                    </button>
                </div>
            </form>
        </main>
    </div>
</template>