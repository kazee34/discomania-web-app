<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import InputPassword from '@/components/ui/input-password.vue';
import { Label } from '@/components/ui/label';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: '/admin/products' },
    { title: 'Clientes', href: '/admin/customers' },
    { title: 'Nuevo cliente' },
];

const form = useForm({
    first_name:              '',
    last_name:               '',
    email:                   '',
    password:                '',
    password_confirmation:   '',
    phone:                   '',
    birth_date:              '',
    dni_nif:                 '',
    shipping_street:         '',
    shipping_street_number:  '',
    shipping_apartment:      '',
    shipping_city:           '',
    shipping_postal_code:    '',
    shipping_state_province: '',
    is_vip:                  false,
});

function submit() {
    form.clearErrors();
    let ok = true;

    if (!form.first_name.trim()) {
        form.setError('first_name', 'El nombre es obligatorio.');
        ok = false;
    } else if (form.first_name.trim().length < 2) {
        form.setError('first_name', 'El nombre debe tener al menos 2 caracteres.');
        ok = false;
    }
    if (!form.last_name.trim()) {
        form.setError('last_name', 'Los apellidos son obligatorios.');
        ok = false;
    } else if (form.last_name.trim().length < 2) {
        form.setError('last_name', 'Los apellidos deben tener al menos 2 caracteres.');
        ok = false;
    }
    if (!form.email.trim()) {
        form.setError('email', 'El correo electrónico es obligatorio.');
        ok = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        form.setError('email', 'El formato del correo electrónico no es válido.');
        ok = false;
    }
    if (!form.password) {
        form.setError('password', 'La contraseña es obligatoria.');
        ok = false;
    } else if (form.password.length < 8) {
        form.setError('password', 'La contraseña debe tener al menos 8 caracteres.');
        ok = false;
    }
    if (form.password && form.password !== form.password_confirmation) {
        form.setError('password_confirmation', 'La confirmación de contraseña no coincide.');
        ok = false;
    }
    if (!form.shipping_street.trim()) {
        form.setError('shipping_street', 'La calle es obligatoria.');
        ok = false;
    }
    if (!form.shipping_street_number.trim()) {
        form.setError('shipping_street_number', 'El número de la calle es obligatorio.');
        ok = false;
    }
    if (!form.shipping_city.trim()) {
        form.setError('shipping_city', 'La ciudad es obligatoria.');
        ok = false;
    }
    if (!form.shipping_postal_code.trim()) {
        form.setError('shipping_postal_code', 'El código postal es obligatorio.');
        ok = false;
    }

    if (!ok) return;
    form.post('/admin/customers');
}
</script>

<template>
    <Head title="Nuevo cliente — Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-2xl">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Nuevo cliente</h1>
                <Button as-child variant="outline" size="sm">
                    <Link href="/admin/customers">Cancelar</Link>
                </Button>
            </div>

            <form class="flex flex-col gap-5" @submit.prevent="submit">
                <!-- Nombre y apellidos -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <Label for="first_name">Nombre</Label>
                        <Input id="first_name" v-model="form.first_name" type="text" placeholder="María" />
                        <InputError :message="form.errors.first_name" />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <Label for="last_name">Apellidos</Label>
                        <Input id="last_name" v-model="form.last_name" type="text" placeholder="García López" />
                        <InputError :message="form.errors.last_name" />
                    </div>
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                    <Label for="email">Correo electrónico</Label>
                    <Input id="email" v-model="form.email" type="email" placeholder="correo@ejemplo.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <!-- Contraseña -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <Label for="password">Contraseña temporal</Label>
                        <InputPassword id="password" v-model="form.password" />
                        <InputError :message="form.errors.password" />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <Label for="password_confirmation">Confirmar contraseña</Label>
                        <InputPassword id="password_confirmation" v-model="form.password_confirmation" />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <!-- Teléfono y fecha de nacimiento -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <Label for="phone">Teléfono <span class="text-xs text-muted-foreground">(opcional)</span></Label>
                        <Input id="phone" v-model="form.phone" type="tel" placeholder="+34 600 000 000" />
                        <InputError :message="form.errors.phone" />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <Label for="birth_date">Fecha de nacimiento <span class="text-xs text-muted-foreground">(opcional)</span></Label>
                        <Input id="birth_date" v-model="form.birth_date" type="date" min="1900-01-01" />
                        <InputError :message="form.errors.birth_date" />
                    </div>
                </div>

                <!-- DNI/NIF -->
                <div class="flex flex-col gap-1.5">
                    <Label for="dni_nif">DNI / NIF <span class="text-xs text-muted-foreground">(opcional)</span></Label>
                    <Input id="dni_nif" v-model="form.dni_nif" type="text" placeholder="12345678A" />
                    <InputError :message="form.errors.dni_nif" />
                </div>

                <!-- Dirección de envío -->
                <div class="space-y-4 rounded-lg border p-4">
                    <p class="text-sm font-semibold">Dirección de envío</p>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2 flex flex-col gap-1.5">
                            <Label for="shipping_street">Calle</Label>
                            <Input id="shipping_street" v-model="form.shipping_street" type="text" placeholder="Calle Gran Vía" />
                            <InputError :message="form.errors.shipping_street" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="shipping_street_number">Número</Label>
                            <Input id="shipping_street_number" v-model="form.shipping_street_number" type="text" placeholder="12" />
                            <InputError :message="form.errors.shipping_street_number" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <Label for="shipping_apartment">Piso / Puerta <span class="text-xs text-muted-foreground">(opcional)</span></Label>
                        <Input id="shipping_apartment" v-model="form.shipping_apartment" type="text" placeholder="3º B" />
                        <InputError :message="form.errors.shipping_apartment" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1.5">
                            <Label for="shipping_city">Ciudad</Label>
                            <Input id="shipping_city" v-model="form.shipping_city" type="text" placeholder="Madrid" />
                            <InputError :message="form.errors.shipping_city" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label for="shipping_postal_code">Código postal</Label>
                            <Input id="shipping_postal_code" v-model="form.shipping_postal_code" type="text" placeholder="28001" />
                            <InputError :message="form.errors.shipping_postal_code" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <Label for="shipping_state_province">Provincia <span class="text-xs text-muted-foreground">(opcional)</span></Label>
                        <Input id="shipping_state_province" v-model="form.shipping_state_province" type="text" placeholder="Madrid" />
                        <InputError :message="form.errors.shipping_state_province" />
                    </div>
                </div>

                <!-- VIP -->
                <div class="flex items-center gap-3">
                    <Checkbox
                        id="is_vip"
                        :checked="form.is_vip"
                        @update:checked="(val) => form.is_vip = val"
                    />
                    <Label for="is_vip" class="cursor-pointer">
                        Cliente VIP
                        <span class="ml-1 text-xs text-muted-foreground">(acceso y beneficios especiales)</span>
                    </Label>
                    <InputError :message="form.errors.is_vip" />
                </div>

                <Button type="submit" :disabled="form.processing" class="mt-1">
                    Crear cliente
                </Button>
            </form>
        </div>
    </AppLayout>
</template>
