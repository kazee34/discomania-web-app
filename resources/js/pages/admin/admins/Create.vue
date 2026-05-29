<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputPassword from '@/components/ui/input-password.vue';
import { Label } from '@/components/ui/label';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: '/admin/products' },
    { title: 'Administradores', href: '/admin/admins' },
    { title: 'Nuevo administrador' },
];

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'editor',
});

function submit() {
    form.clearErrors();
    let ok = true;

    if (!form.name.trim()) {
        form.setError('name', 'El nombre es obligatorio.');
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

    if (!ok) return;
    form.post('/admin/admins');
}
</script>

<template>
    <Head title="Nuevo administrador — Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-lg">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Nuevo administrador</h1>
                <Button as-child variant="outline" size="sm">
                    <Link href="/admin/admins">Cancelar</Link>
                </Button>
            </div>

            <form class="flex flex-col gap-4" @submit.prevent="submit">
                <div class="flex flex-col gap-1.5">
                    <Label for="name">Nombre</Label>
                    <Input id="name" v-model="form.name" type="text" placeholder="Nombre completo" />
                    <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <Label for="email">Email</Label>
                    <Input id="email" v-model="form.email" type="email" placeholder="correo@ejemplo.com" />
                    <p v-if="form.errors.email" class="text-xs text-destructive">{{ form.errors.email }}</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <Label for="role">Rol</Label>
                    <select
                        id="role"
                        v-model="form.role"
                        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm"
                    >
                        <option value="admin">Admin</option>
                        <option value="editor">Gestor</option>
                    </select>
                    <p v-if="form.errors.role" class="text-xs text-destructive">{{ form.errors.role }}</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <Label for="password">Contraseña</Label>
                    <InputPassword id="password" v-model="form.password" />
                    <p v-if="form.errors.password" class="text-xs text-destructive">{{ form.errors.password }}</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <Label for="password_confirmation">Confirmar contraseña</Label>
                    <InputPassword id="password_confirmation" v-model="form.password_confirmation" />
                    <p v-if="form.errors.password_confirmation" class="text-xs text-destructive">{{ form.errors.password_confirmation }}</p>
                </div>

                <Button type="submit" :disabled="form.processing" class="mt-2">
                    Crear administrador
                </Button>
            </form>
        </div>
    </AppLayout>
</template>
