<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem } from '@/types';

interface Admin {
    id: number;
    userId: number;
    name: string;
    email: string;
    role: string;
    isActive: boolean;
    createdAt: string | null;
}

defineProps<{ admins: Admin[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: '/admin/products' },
    { title: 'Administradores', href: '/admin/admins' },
];

const roleLabel: Record<string, string> = {
    super_admin: 'Super Admin',
    admin: 'Admin',
    editor: 'Editor',
};

function deleteAdmin(id: number) {
    if (!confirm('¿Eliminar este administrador? Esta acción no se puede deshacer.')) return;
    useForm({}).delete(`/admin/admins/${id}`, { preserveScroll: true });
}

function toggle(id: number, activate: boolean) {
    useForm({ activate }).patch(`/admin/admins/${id}/toggle`, { preserveScroll: true });
}
</script>

<template>
    <Head title="Admin Panel — Administradores" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Administradores</h1>
                    <p class="text-sm text-muted-foreground mt-0.5">{{ admins.length }} administradores registrados</p>
                </div>
                <Button as-child>
                    <Link href="/admin/admins/create">Añadir administrador</Link>
                </Button>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $page.props.flash.error }}
            </div>

            <div class="rounded-xl border overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3 text-left">Nombre</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Email</th>
                            <th class="px-4 py-3 text-left">Rol</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="admin in admins" :key="admin.id" class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3 font-medium">{{ admin.name }}</td>
                            <td class="px-4 py-3 hidden md:table-cell text-muted-foreground">{{ admin.email }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium bg-muted text-muted-foreground">
                                    {{ roleLabel[admin.role] ?? admin.role }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="admin.isActive ? 'bg-green-100 text-green-800' : 'bg-muted text-muted-foreground'"
                                >
                                    {{ admin.isActive ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <Button
                                        v-if="admin.role !== 'super_admin'"
                                        size="sm"
                                        variant="outline"
                                        @click="toggle(admin.id, !admin.isActive)"
                                    >
                                        {{ admin.isActive ? 'Desactivar' : 'Activar' }}
                                    </Button>
                                    <Button
                                        v-if="admin.role !== 'super_admin'"
                                        size="sm"
                                        variant="destructive"
                                        @click="deleteAdmin(admin.id)"
                                    >
                                        Eliminar
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
