<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import ProfileLayout from '@/components/profile/ProfileLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

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

const form = useForm({
    firstName:             props.profile.firstName,
    lastName:              props.profile.lastName,
    phone:                 props.profile.phone ?? '',
    shippingStreet:        props.profile.shippingStreet ?? '',
    shippingStreetNumber:  props.profile.shippingStreetNumber ?? '',
    shippingApartment:     props.profile.shippingApartment ?? '',
    shippingCity:          props.profile.shippingCity ?? '',
    shippingPostalCode:    props.profile.shippingPostalCode ?? '',
    shippingStateProvince: props.profile.shippingStateProvince ?? '',
    shippingCountry:       props.profile.shippingCountry ?? '',
});

function submit() {
    form.put('/profile');
}
</script>

<template>
    <Head title="Mi perfil — Discomania" />

    <ProfileLayout>
        <div class="flex flex-col gap-8">
            <div>
                <h1 class="text-2xl font-bold">Datos personales</h1>
                <p class="text-sm text-muted-foreground mt-1">
                    Gestiona tu información de contacto y dirección de envío.
                </p>
            </div>

            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <!-- Identidad (read-only) -->
                <section class="rounded-xl border bg-card p-5 flex flex-col gap-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Identidad</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-1.5">
                            <Label>Correo electrónico</Label>
                            <Input :value="profile.email" disabled />
                            <p class="text-xs text-muted-foreground">Modifícalo en Configuración</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label>DNI / NIF</Label>
                            <Input :value="profile.dniNif ?? '—'" disabled />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label>Fecha de nacimiento</Label>
                            <Input :value="profile.birthDate ?? '—'" disabled />
                        </div>
                    </div>
                </section>

                <!-- Datos de contacto -->
                <section class="rounded-xl border bg-card p-5 flex flex-col gap-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Contacto</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-1.5">
                            <Label for="firstName">Nombre</Label>
                            <Input id="firstName" v-model="form.firstName" />
                            <p v-if="form.errors.firstName" class="text-xs text-destructive">{{ form.errors.firstName }}</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="lastName">Apellidos</Label>
                            <Input id="lastName" v-model="form.lastName" />
                            <p v-if="form.errors.lastName" class="text-xs text-destructive">{{ form.errors.lastName }}</p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="phone">Teléfono</Label>
                            <Input id="phone" v-model="form.phone" type="tel" />
                        </div>
                    </div>
                </section>

                <!-- Dirección de envío -->
                <section class="rounded-xl border bg-card p-5 flex flex-col gap-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">Dirección de envío</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="flex flex-col gap-1.5 sm:col-span-2">
                            <Label for="shippingStreet">Calle</Label>
                            <Input id="shippingStreet" v-model="form.shippingStreet" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="shippingStreetNumber">Número</Label>
                            <Input id="shippingStreetNumber" v-model="form.shippingStreetNumber" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="shippingApartment">Piso / Puerta</Label>
                            <Input id="shippingApartment" v-model="form.shippingApartment" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="shippingCity">Ciudad</Label>
                            <Input id="shippingCity" v-model="form.shippingCity" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="shippingPostalCode">Código postal</Label>
                            <Input id="shippingPostalCode" v-model="form.shippingPostalCode" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="shippingStateProvince">Provincia</Label>
                            <Input id="shippingStateProvince" v-model="form.shippingStateProvince" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="shippingCountry">País</Label>
                            <Input id="shippingCountry" v-model="form.shippingCountry" />
                        </div>
                    </div>
                </section>

                <div class="flex items-center gap-4">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
                    </Button>
                    <p v-if="$page.props.flash?.success" class="text-sm text-green-600">
                        {{ $page.props.flash.success }}
                    </p>
                </div>
            </form>
        </div>
    </ProfileLayout>
</template>