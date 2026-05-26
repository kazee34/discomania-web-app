<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';

interface Customer {
    id: number;
    firstName: string;
    lastName: string;
    phone: string;
    dniNif: string;
    totalOrders: number;
    isActive: boolean;
    isVip: boolean;
    createdAt: string | null;
}

const props = defineProps<{ customers: Customer[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: '/admin/products' },
    { title: 'Clientes', href: '/admin/customers' },
];

const search    = ref('');
const filterVip = ref<'all' | 'vip' | 'standard'>('all');
const filterStatus = ref<'all' | 'active' | 'inactive'>('all');

const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return props.customers.filter((c) => {
        const matchesSearch =
            !q ||
            `${c.firstName} ${c.lastName}`.toLowerCase().includes(q) ||
            (c.phone?.toLowerCase() ?? '').includes(q) ||
            (c.dniNif?.toLowerCase() ?? '').includes(q);
        const matchesVip =
            filterVip.value === 'all' ||
            (filterVip.value === 'vip' && c.isVip) ||
            (filterVip.value === 'standard' && !c.isVip);
        const matchesStatus =
            filterStatus.value === 'all' ||
            (filterStatus.value === 'active' && c.isActive) ||
            (filterStatus.value === 'inactive' && !c.isActive);
        return matchesSearch && matchesVip && matchesStatus;
    });
});

function toggleStatus(id: number, activate: boolean) {
    const msg = activate ? '¿Activar este cliente?' : '¿Desactivar este cliente?';
    if (!confirm(msg)) return;
    useForm({ activate }).patch(`/admin/customers/${id}/deactivate`, { preserveScroll: true });
}
</script>

<template>
    <Head title="Admin Panel — Clientes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Clientes</h1>
                    <p class="text-sm text-muted-foreground mt-0.5">{{ filtered.length }} de {{ customers.length }} clientes</p>
                </div>
                <Button as-child>
                    <Link href="/admin/customers/create">Añadir cliente</Link>
                </Button>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $page.props.flash.success }}
            </div>

            <!-- Filtros -->
            <div class="flex flex-wrap gap-3">
                <Input v-model="search" placeholder="Buscar por nombre, teléfono o DNI..." class="max-w-xs" />
                <select
                    v-model="filterVip"
                    class="flex h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm"
                >
                    <option value="all">Todos (VIP)</option>
                    <option value="vip">Solo VIP</option>
                    <option value="standard">No VIP</option>
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
                            <th class="px-4 py-3 text-left">Cliente</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">DNI/NIF</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Teléfono</th>
                            <th class="px-4 py-3 text-right hidden sm:table-cell">Pedidos</th>
                            <th class="px-4 py-3 text-center">VIP</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-if="filtered.length === 0">
                            <td colspan="7" class="px-4 py-8 text-center text-muted-foreground">
                                No se encontraron clientes con esos filtros.
                            </td>
                        </tr>
                        <tr v-for="customer in filtered" :key="customer.id" class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ customer.firstName }} {{ customer.lastName }}</p>
                                <p v-if="customer.createdAt" class="text-xs text-muted-foreground">Alta: {{ customer.createdAt }}</p>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell text-muted-foreground">{{ customer.dniNif }}</td>
                            <td class="px-4 py-3 hidden md:table-cell text-muted-foreground">{{ customer.phone }}</td>
                            <td class="px-4 py-3 text-right hidden sm:table-cell">{{ customer.totalOrders }}</td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    v-if="customer.isVip"
                                    class="rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800"
                                >
                                    VIP
                                </span>
                                <span v-else class="text-muted-foreground text-xs">—</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="customer.isActive
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-muted text-muted-foreground'"
                                >
                                    {{ customer.isActive ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <Button as-child size="sm" variant="outline" class="border-violet-600 text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-950">
                                        <Link :href="`/admin/customers/${customer.id}`">Ver</Link>
                                    </Button>
                                    <Button
                                        size="sm"
                                        :variant="customer.isActive ? 'destructive' : 'outline'"
                                        @click="toggleStatus(customer.id, !customer.isActive)"
                                    >
                                        {{ customer.isActive ? 'Desactivar' : 'Activar' }}
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
