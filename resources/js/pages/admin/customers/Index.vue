<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem } from '@/types';

interface Customer {
    id: number;
    firstName: string;
    lastName: string;
    phone: string;
    dniNif: string;
    totalOrders: number;
    isActive: boolean;
    createdAt: string | null;
}

defineProps<{ customers: Customer[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: '/admin/products' },
    { title: 'Clientes', href: '/admin/customers' },
];

function deactivate(id: number) {
    if (!confirm('¿Desactivar este cliente?')) return;
    useForm({}).patch(`/admin/customers/${id}/deactivate`, { preserveScroll: true });
}
</script>

<template>
    <Head title="Admin Panel — Clientes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div>
                <h1 class="text-2xl font-bold">Clientes</h1>
                <p class="text-sm text-muted-foreground mt-0.5">{{ customers.length }} clientes registrados</p>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $page.props.flash.success }}
            </div>

            <div class="rounded-xl border overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3 text-left">Cliente</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">DNI/NIF</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Teléfono</th>
                            <th class="px-4 py-3 text-right hidden sm:table-cell">Pedidos</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="customer in customers" :key="customer.id" class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ customer.firstName }} {{ customer.lastName }}</p>
                                <p v-if="customer.createdAt" class="text-xs text-muted-foreground">Alta: {{ customer.createdAt }}</p>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell text-muted-foreground">{{ customer.dniNif }}</td>
                            <td class="px-4 py-3 hidden md:table-cell text-muted-foreground">{{ customer.phone }}</td>
                            <td class="px-4 py-3 text-right hidden sm:table-cell">{{ customer.totalOrders }}</td>
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
                                    <Button as-child size="sm" variant="outline">
                                        <Link :href="`/admin/customers/${customer.id}`">Ver</Link>
                                    </Button>
                                    <Button
                                        v-if="customer.isActive"
                                        size="sm"
                                        variant="destructive"
                                        @click="deactivate(customer.id)"
                                    >
                                        Desactivar
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
