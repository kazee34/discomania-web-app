<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { computed, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import type { BreadcrumbItem } from '@/types';

interface Order {
    id: number;
    orderNumber: string;
    customerId: number;
    orderDate: string;
    totalAmount: number;
    status: string;
    itemCount: number;
}

const props = defineProps<{ orders: Order[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: '/admin/products' },
    { title: 'Pedidos', href: '/admin/orders' },
];

const statusConfig: Record<string, { label: string; class: string }> = {
    pending:    { label: 'Pendiente',   class: 'bg-yellow-100 text-yellow-800' },
    processing: { label: 'En proceso',  class: 'bg-blue-100 text-blue-800' },
    shipped:    { label: 'Enviado',     class: 'bg-purple-100 text-purple-800' },
    delivered:  { label: 'Entregado',   class: 'bg-green-100 text-green-800' },
    cancelled:  { label: 'Cancelado',   class: 'bg-red-100 text-red-800' },
};

const search       = ref('');
const filterStatus = ref('all');

const filtered = computed(() => {
    const q = search.value.toLowerCase();
    return props.orders.filter((o) => {
        const matchesSearch  = !q || o.orderNumber.toLowerCase().includes(q);
        const matchesStatus  = filterStatus.value === 'all' || o.status === filterStatus.value;
        return matchesSearch && matchesStatus;
    });
});
</script>

<template>
    <Head title="Admin Panel — Pedidos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <div>
                <h1 class="text-2xl font-bold">Pedidos</h1>
                <p class="text-sm text-muted-foreground mt-0.5">{{ filtered.length }} de {{ orders.length }} pedidos</p>
            </div>

            <!-- Filtros -->
            <div class="flex flex-wrap gap-3">
                <Input v-model="search" placeholder="Buscar por nº de pedido..." class="max-w-xs" />
                <select
                    v-model="filterStatus"
                    class="flex h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm"
                >
                    <option value="all">Todos (estado)</option>
                    <option value="pending">Pendiente</option>
                    <option value="processing">En proceso</option>
                    <option value="shipped">Enviado</option>
                    <option value="delivered">Entregado</option>
                    <option value="cancelled">Cancelado</option>
                </select>
            </div>

            <div class="rounded-xl border overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3 text-left">Nº Pedido</th>
                            <th class="px-4 py-3 text-left hidden md:table-cell">Fecha</th>
                            <th class="px-4 py-3 text-right hidden sm:table-cell">Artículos</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-if="filtered.length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-muted-foreground">
                                No se encontraron pedidos con esos filtros.
                            </td>
                        </tr>
                        <tr v-for="order in filtered" :key="order.id" class="hover:bg-muted/30 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs font-semibold">{{ order.orderNumber }}</td>
                            <td class="px-4 py-3 hidden md:table-cell text-muted-foreground">{{ order.orderDate }}</td>
                            <td class="px-4 py-3 text-right hidden sm:table-cell">{{ order.itemCount }}</td>
                            <td class="px-4 py-3 text-right font-medium">{{ order.totalAmount.toFixed(2) }} €</td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="statusConfig[order.status]?.class ?? 'bg-muted text-muted-foreground'"
                                >
                                    {{ statusConfig[order.status]?.label ?? order.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <Button as-child size="sm" variant="outline" class="border-violet-600 text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-950">
                                    <Link :href="`/admin/orders/${order.orderNumber}`">Ver detalle</Link>
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
