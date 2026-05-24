<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { CalendarDays, MapPin, Pencil, Phone } from 'lucide-vue-next';
import ProfileLayout from '@/components/profile/ProfileLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { getInitials } from '@/composables/useInitials';
import type { User } from '@/types';

interface Profile {
    email: string;
    firstName: string;
    lastName: string;
    phone: string | null;
    birthDate: string | null;
    dniNif: string | null;
    shippingStreet: string | null;
    shippingStreetNumber: string | null;
    shippingApartment: string | null;
    shippingCity: string | null;
    shippingPostalCode: string | null;
    shippingStateProvince: string | null;
    shippingCountry: string | null;
}

const props = defineProps<{ profile: Profile }>();

const page = usePage();
const authUser = computed(() => page.props.auth?.user as User | null);

const activeTab = ref<'overview' | 'edit'>('overview');

const form = useForm({
    firstName:             props.profile.firstName,
    lastName:              props.profile.lastName,
    phone:                 props.profile.phone ?? '',
    birthDate:             props.profile.birthDate ?? '',
    shippingStreet:        props.profile.shippingStreet ?? '',
    shippingStreetNumber:  props.profile.shippingStreetNumber ?? '',
    shippingApartment:     props.profile.shippingApartment ?? '',
    shippingCity:          props.profile.shippingCity ?? '',
    shippingPostalCode:    props.profile.shippingPostalCode ?? '',
    shippingStateProvince: props.profile.shippingStateProvince ?? '',
    shippingCountry:       props.profile.shippingCountry ?? '',
});

const maxBirthDate = computed(() => {
    const d = new Date();
    d.setFullYear(d.getFullYear() - 16);
    return d.toISOString().split('T')[0];
});

const birthDateFormatted = computed(() => {
    if (!props.profile.birthDate) return null;
    return new Date(props.profile.birthDate).toLocaleDateString('es-ES', { dateStyle: 'long' });
});

const addressSummary = computed(() => {
    const { shippingStreet, shippingStreetNumber, shippingCity, shippingPostalCode } = props.profile;
    const street = [shippingStreet, shippingStreetNumber].filter(Boolean).join(' ');
    const city = [shippingPostalCode, shippingCity].filter(Boolean).join(' ');
    return [street, city].filter(Boolean).join(', ') || null;
});

function submit() {
    form.put('/profile', {
        onSuccess: () => { activeTab.value = 'overview'; },
    });
}
</script>

<template>
    <Head title="Mi perfil — Discomania" />

    <ProfileLayout>
        <div class="flex flex-col gap-6">

            <!-- Flash message -->
            <div
                v-if="$page.props.flash?.success"
                class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400"
            >
                {{ $page.props.flash.success }}
            </div>

            <!-- Tabs -->
            <div class="flex gap-0 border-b">
                <button
                    @click="activeTab = 'overview'"
                    :class="[
                        'px-5 py-3 text-sm font-medium border-b-2 -mb-px transition-colors',
                        activeTab === 'overview'
                            ? 'border-violet-600 text-violet-600'
                            : 'border-transparent text-muted-foreground hover:text-foreground',
                    ]"
                >
                    Mi perfil
                </button>
                <button
                    @click="activeTab = 'edit'"
                    :class="[
                        'px-5 py-3 text-sm font-medium border-b-2 -mb-px transition-colors',
                        activeTab === 'edit'
                            ? 'border-violet-600 text-violet-600'
                            : 'border-transparent text-muted-foreground hover:text-foreground',
                    ]"
                >
                    Datos personales
                </button>
            </div>

            <!-- ── OVERVIEW TAB ── -->
            <div v-if="activeTab === 'overview'" class="flex flex-col gap-6">

                <!-- Hero card -->
                <div class="relative overflow-hidden rounded-2xl bg-linear-to-br from-violet-900 to-black p-8 text-white">
                    <div class="pointer-events-none absolute -top-16 -right-16 h-48 w-48 rounded-full bg-violet-500/20 blur-3xl" />
                    <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center">
                        <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-white/20 text-3xl font-bold ring-4 ring-white/20">
                            {{ getInitials(authUser?.name) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h1 class="text-2xl font-bold leading-tight truncate">
                                {{ profile.firstName }} {{ profile.lastName }}
                            </h1>
                            <p class="mt-1 text-white/60 truncate">{{ profile.email }}</p>
                        </div>
                        <Button
                            @click="activeTab = 'edit'"
                            class="shrink-0 bg-white/15 text-white hover:bg-white/25 border-white/20 border"
                            variant="ghost"
                        >
                            <Pencil class="mr-2 h-4 w-4" />
                            Editar datos
                        </Button>
                    </div>
                </div>

                <!-- Info cards -->
                <div class="grid gap-4 sm:grid-cols-3">
                    <!-- Teléfono -->
                    <div class="flex flex-col gap-3 rounded-2xl border bg-card p-5">
                        <div class="flex items-center gap-2 text-muted-foreground">
                            <Phone class="h-4 w-4 text-violet-500" />
                            <span class="text-xs font-semibold uppercase tracking-wide">Teléfono</span>
                        </div>
                        <p v-if="profile.phone" class="text-base font-medium">{{ profile.phone }}</p>
                        <button
                            v-else
                            @click="activeTab = 'edit'"
                            class="text-left text-sm text-muted-foreground hover:text-violet-600 transition-colors"
                        >
                            + Añadir teléfono
                        </button>
                    </div>

                    <!-- Fecha de nacimiento -->
                    <div class="flex flex-col gap-3 rounded-2xl border bg-card p-5">
                        <div class="flex items-center gap-2 text-muted-foreground">
                            <CalendarDays class="h-4 w-4 text-violet-500" />
                            <span class="text-xs font-semibold uppercase tracking-wide">Nacimiento</span>
                        </div>
                        <p v-if="birthDateFormatted" class="text-base font-medium">{{ birthDateFormatted }}</p>
                        <button
                            v-else
                            @click="activeTab = 'edit'"
                            class="text-left text-sm text-muted-foreground hover:text-violet-600 transition-colors"
                        >
                            + Añadir fecha
                        </button>
                    </div>

                    <!-- DNI / NIF -->
                    <div class="flex flex-col gap-3 rounded-2xl border bg-card p-5">
                        <div class="flex items-center gap-2 text-muted-foreground">
                            <span class="text-xs font-semibold uppercase tracking-wide">DNI / NIF</span>
                        </div>
                        <p v-if="profile.dniNif" class="font-mono text-base font-medium">{{ profile.dniNif }}</p>
                        <p v-else class="text-sm text-muted-foreground">No especificado</p>
                    </div>
                </div>

                <!-- Dirección -->
                <div class="flex flex-col gap-3 rounded-2xl border bg-card p-5">
                    <div class="flex items-center gap-2 text-muted-foreground">
                        <MapPin class="h-4 w-4 text-violet-500" />
                        <span class="text-xs font-semibold uppercase tracking-wide">Dirección de envío</span>
                    </div>
                    <p v-if="addressSummary" class="text-base font-medium">{{ addressSummary }}</p>
                    <button
                        v-else
                        @click="activeTab = 'edit'"
                        class="text-left text-sm text-muted-foreground hover:text-violet-600 transition-colors"
                    >
                        + Añadir dirección
                    </button>
                </div>
            </div>

            <!-- ── EDIT TAB ── -->
            <div v-else class="flex flex-col gap-6">
                <div>
                    <h2 class="text-xl font-bold">Datos personales</h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Gestiona tu información de contacto y dirección de envío.
                    </p>
                </div>

                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <!-- Contacto -->
                    <section class="rounded-2xl border bg-card p-6 flex flex-col gap-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Contacto</h3>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="flex flex-col gap-2">
                                <Label for="firstName">Nombre</Label>
                                <Input id="firstName" v-model="form.firstName" />
                                <p v-if="form.errors.firstName" class="text-xs text-destructive">{{ form.errors.firstName }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <Label for="lastName">Apellidos</Label>
                                <Input id="lastName" v-model="form.lastName" />
                                <p v-if="form.errors.lastName" class="text-xs text-destructive">{{ form.errors.lastName }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <Label for="phone">Teléfono</Label>
                                <Input id="phone" v-model="form.phone" type="tel" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <Label for="birthDate">Fecha de nacimiento</Label>
                                <Input
                                    id="birthDate"
                                    v-model="form.birthDate"
                                    type="date"
                                    min="1900-01-01"
                                    :max="maxBirthDate"
                                />
                                <p v-if="form.errors.birthDate" class="text-xs text-destructive">{{ form.errors.birthDate }}</p>
                            </div>
                        </div>
                    </section>

                    <!-- Identidad (read-only) -->
                    <section v-if="profile.dniNif" class="rounded-2xl border bg-card p-6 flex flex-col gap-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Identidad</h3>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="flex flex-col gap-2">
                                <Label>DNI / NIF</Label>
                                <Input :value="profile.dniNif" disabled />
                            </div>
                        </div>
                    </section>

                    <!-- Dirección -->
                    <section class="rounded-2xl border bg-card p-6 flex flex-col gap-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Dirección de envío</h3>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div class="flex flex-col gap-2 sm:col-span-2">
                                <Label for="shippingStreet">Calle</Label>
                                <Input id="shippingStreet" v-model="form.shippingStreet" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <Label for="shippingStreetNumber">Número</Label>
                                <Input id="shippingStreetNumber" v-model="form.shippingStreetNumber" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <Label for="shippingApartment">Piso / Puerta</Label>
                                <Input id="shippingApartment" v-model="form.shippingApartment" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <Label for="shippingCity">Ciudad</Label>
                                <Input id="shippingCity" v-model="form.shippingCity" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <Label for="shippingPostalCode">Código postal</Label>
                                <Input id="shippingPostalCode" v-model="form.shippingPostalCode" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <Label for="shippingStateProvince">Provincia</Label>
                                <Input id="shippingStateProvince" v-model="form.shippingStateProvince" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <Label for="shippingCountry">País</Label>
                                <Input id="shippingCountry" v-model="form.shippingCountry" />
                            </div>
                        </div>
                    </section>

                    <div class="flex items-center gap-4">
                        <Button type="submit" class="bg-violet-600 hover:bg-violet-700 text-white" :disabled="form.processing">
                            {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
                        </Button>
                        <Button type="button" variant="ghost" @click="activeTab = 'overview'">
                            Cancelar
                        </Button>
                    </div>
                </form>
            </div>

        </div>
    </ProfileLayout>
</template>
