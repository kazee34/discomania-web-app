<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
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

const props = defineProps<{ admins: Admin[] }>();

const page = usePage();

function canAct(target: Admin): boolean {
    const myUserId = page.props.auth.user?.id;
    const myRole   = page.props.adminRole;

    if (target.userId === myUserId) return false;          // nunca sobre uno mismo
    if (myRole === 'super_admin')   return true;           // super_admin puede con todos
    if (myRole === 'admin')         return target.role === 'editor'; // admin solo con editors
    return false;                                          // editor no puede con nadie
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: '/admin/products' },
    { title: 'Administradores', href: '/admin/admins' },
];

const roleLabel: Record<string, string> = {
    super_admin: 'Super Admin',
    admin: 'Admin',
    editor: 'Gestor',
};

const search       = ref('');
const filterRole   = ref('all');
const filterStatus = ref<'all' | 'active' | 'inactive'>('all');

const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return props.admins.filter((a) => {
        const matchesSearch =
            !q ||
            a.name.toLowerCase().includes(q) ||
            a.email.toLowerCase().includes(q);
        const matchesRole   = filterRole.value === 'all' || a.role === filterRole.value;
        const matchesStatus =
            filterStatus.value === 'all' ||
            (filterStatus.value === 'active' && a.isActive) ||
            (filterStatus.value === 'inactive' && !a.isActive);
        return matchesSearch && matchesRole && matchesStatus;
    });
});

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
                    <p class="text-sm text-muted-foreground mt-0.5">{{ filtered.length }} de {{ admins.length }} administradores</p>
                </div>
                <Button v-if="['super_admin', 'admin'].includes(page.props.adminRole ?? '')" as-child>
                    <Link href="/admin/admins/create">Añadir administrador</Link>
                </Button>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $page.props.flash.error }}
            </div>

            <!-- Filtros -->
            <div class="flex flex-wrap gap-3">
                <Input v-model="search" placeholder="Buscar por nombre o email..." class="max-w-xs" />
                <select
                    v-model="filterRole"
                    class="flex h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm"
                >
                    <option value="all">Todos (rol)</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="editor">Gestor</option>
                </select>
                <select
                    v-model="filterStatus"
                    class="flex h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm"
                >
                    <option value="all">Todos (estado)</option>
                    <option value="active">Activos</option>
                    <option value="inactive">Inactivos</option>
                </select>
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
                        <tr v-if="filtered.length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-muted-foreground">
                                No se encontraron administradores con esos filtros.
                            </td>
                        </tr>
                        <tr v-for="admin in filtered" :key="admin.id" class="hover:bg-muted/30 transition-colors">
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
                                        v-if="canAct(admin)"
                                        size="sm"
                                        variant="outline"
                                        @click="toggle(admin.id, !admin.isActive)"
                                    >
                                        {{ admin.isActive ? 'Desactivar' : 'Activar' }}
                                    </Button>
                                    <Button
                                        v-if="canAct(admin)"
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
