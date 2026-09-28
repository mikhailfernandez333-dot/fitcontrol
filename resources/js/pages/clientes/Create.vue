<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
    nombre: '',
    apellido: '',
    ci: '',
    telefono: '',
    email: '',
    fecha_nacimiento: '',
    foto: null as File | null,
    activo: true,
})

const fotoPreview = ref<string | null>(null)

const seleccionarFoto = (event: Event) => {
    const input = event.target as HTMLInputElement
    const archivo = input.files?.[0]

    if (!archivo) return

    form.foto = archivo

    fotoPreview.value = URL.createObjectURL(archivo)
}

const quitarFoto = () => {
    form.foto = null
    fotoPreview.value = null
}


const submit = () => {
    form.post('/clientes', {
        forceFormData: true,
    })
}


</script>

<template>
    <Head title="Nuevo cliente" />

    <div class="min-h-screen bg-slate-50 p-6">
        <div class="mx-auto max-w-4xl">

            <!-- Encabezado -->
            <div class="mb-8">
                <Link
                    href="/clientes"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800"
                >
                    ← Volver a clientes
                </Link>

                <div class="mt-4">
                    <p class="text-sm font-semibold tracking-wide text-blue-600">
                        FITCONTROL
                    </p>

                    <h1 class="mt-1 text-3xl font-bold text-slate-900">
                        Registrar nuevo cliente
                    </h1>

                    <p class="mt-2 text-slate-500">
                        Completa los datos del cliente para registrarlo en el gimnasio.
                    </p>
                </div>
            </div>

            <!-- Formulario -->
            <form
                @submit.prevent="submit"
                class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200"
            >
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="font-semibold text-slate-900">
                        Información personal
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Los campos marcados son necesarios para crear el registro.
                    </p>
                </div>

                <div class="grid gap-6 p-6 md:grid-cols-2">


<!-- Foto del cliente -->
<div class="md:col-span-2">
    <label class="mb-2 block text-sm font-medium text-slate-700">
        Foto del cliente
    </label>

    <div class="flex flex-col items-center gap-5 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 sm:flex-row">

        <!-- Vista previa -->
        <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-slate-200 ring-4 ring-white shadow-sm">
            <img
                v-if="fotoPreview"
                :src="fotoPreview"
                alt="Vista previa"
                class="h-full w-full object-cover"
            />

            <span
                v-else
                class="text-4xl"
            >
                👤
            </span>
        </div>

        <div class="flex-1 text-center sm:text-left">
            <p class="font-semibold text-slate-800">
                Foto de perfil
            </p>

            <p class="mt-1 text-sm text-slate-500">
                Selecciona una imagen JPG, PNG o WEBP. Máximo 2 MB.
            </p>

            <div class="mt-4 flex flex-wrap justify-center gap-3 sm:justify-start">
                <label
                    class="cursor-pointer rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-700"
                >
                    📷 Seleccionar foto

                    <input
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="hidden"
                        @change="seleccionarFoto"
                    />
                </label>

                <button
                    v-if="fotoPreview"
                    type="button"
                    @click="quitarFoto"
                    class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100"
                >
                    Quitar foto
                </button>
            </div>

            <p
                v-if="form.errors.foto"
                class="mt-2 text-sm text-red-500"
            >
                {{ form.errors.foto }}
            </p>
        </div>
    </div>
</div>


                    <!-- Nombre -->
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Nombre
                        </label>

                        <input
                            v-model="form.nombre"
                            type="text"
                            
                            placeholder="Ej. Juan"
                            class="w-full rounded-xl border  border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
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
                            class="w-full rounded-xl border border-slate-300 text-slate-900 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
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
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
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
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
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
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
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
                        {{ form.processing ? 'Guardando...' : 'Registrar cliente' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>