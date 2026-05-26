<script setup lang="ts">
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Switch } from '@/components/ui/switch';
import { Label } from '@/components/ui/label';
import type { BreadcrumbItem } from '@/types';

interface Address {
    street: string;
    street_number: string;
    apartment: string | null;
    city: string;
    postal_code: string;
    state_province: string;
    country: string;
    iso_country_code: string;
}

interface Customer {
    id: number;
    firstName: string;
    lastName: string;
    phone: string;
    dniNif: string;
    birthDate: string | null;
    totalOrders: number;
    isActive: boolean;
    isVip: boolean;
    createdAt: string | null;
    address: Address;
}

interface CustomerOrder {
    orderNumber: string;
    orderDate: string;
    totalAmount: number;
    status: string;
}

const props = defineProps<{ customer: Customer; orders: CustomerOrder[] }>();

const page = usePage();
const canToggleVip = computed(() =>
    ['super_admin', 'admin'].includes(page.props.adminRole ?? ''),
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: '/admin/products' },
    { title: 'Clientes', href: '/admin/customers' },
    { title: `${props.customer.firstName} ${props.customer.lastName}` },
];

function toggleStatus() {
    const activate = !props.customer.isActive;
    const msg = activate ? '¿Activar este cliente?' : '¿Desactivar este cliente?';
    if (!confirm(msg)) return;
    useForm({ activate }).patch(`/admin/customers/${props.customer.id}/deactivate`);
}

function toggleVip(value: boolean) {
    useForm({ is_vip: value }).patch(`/admin/customers/${props.customer.id}/vip`, { preserveScroll: true });
}
</script>

<template>
    <Head :title="`${customer.firstName} ${customer.lastName} — Admin`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">{{ customer.firstName }} {{ customer.lastName }}</h1>
                    <p class="text-sm text-muted-foreground mt-0.5">Cliente #{{ customer.id }}</p>
                </div>
                <div class="flex gap-2">
                    <Button as-child variant="outline" size="sm">
                        <Link href="/admin/customers">Volver</Link>
                    </Button>
                    <Button
                        size="sm"
                        :variant="customer.isActive ? 'destructive' : 'outline'"
                        @click="toggleStatus"
                    >
                        {{ customer.isActive ? 'Desactivar' : 'Activar' }}
                    </Button>
                </div>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $page.props.flash.success }}
            </div>

            <!-- Customer data card -->
            <div class="rounded-xl border bg-card divide-y max-w-2xl">
                <div class="grid grid-cols-3 gap-4 p-4">
                    <div>
                        <p class="text-xs text-muted-foreground">Estado</p>
                        <span
                            class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium"
                            :class="customer.isActive ? 'bg-green-100 text-green-800' : 'bg-muted text-muted-foreground'"
                        >
                            {{ customer.isActive ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground">Pedidos totales</p>
                        <p class="font-medium mt-1">{{ customer.totalOrders }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground mb-2">VIP</p>
                        <div class="flex items-center gap-2.5">
                            <Switch
                                :model-value="customer.isVip"
                                :disabled="!canToggleVip"
                                @update:model-value="toggleVip"
                            />
                            <Label class="text-sm" :class="!canToggleVip ? 'text-muted-foreground' : ''">
                                {{ customer.isVip ? 'VIP' : 'Estándar' }}
                            </Label>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 p-4">
                    <div>
                        <p class="text-xs text-muted-foreground">DNI/NIF</p>
                        <p class="font-medium mt-1">{{ customer.dniNif }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground">Teléfono</p>
                        <p class="font-medium mt-1">{{ customer.phone }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 p-4">
                    <div>
                        <p class="text-xs text-muted-foreground">Fecha de nacimiento</p>
                        <p class="font-medium mt-1">{{ customer.birthDate ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground">Alta</p>
                        <p class="font-medium mt-1">{{ customer.createdAt ?? '—' }}</p>
                    </div>
                </div>

                <div class="p-4">
                    <p class="text-xs text-muted-foreground mb-2">Dirección de envío</p>
                    <p class="font-medium">{{ customer.address.street }}, {{ customer.address.street_number }}<span v-if="customer.address.apartment">, {{ customer.address.apartment }}</span></p>
                    <p class="text-sm text-muted-foreground">{{ customer.address.postal_code }} {{ customer.address.city }}, {{ customer.address.state_province }}</p>
                    <p class="text-sm text-muted-foreground">{{ customer.address.country }}</p>
                </div>
            </div>

            <!-- Orders list -->
            <div class="rounded-xl border overflow-hidden">
                <div class="px-4 py-3 bg-muted/50 flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Pedidos</p>
                    <span class="text-xs text-muted-foreground">{{ orders.length }} pedido{{ orders.length !== 1 ? 's' : '' }}</span>
                </div>
                <div v-if="orders.length === 0" class="px-4 py-6 text-center text-sm text-muted-foreground">
                    Sin pedidos
                </div>
                <table v-else class="w-full text-sm">
                    <thead class="bg-muted/30 text-xs uppercase tracking-wide text-muted-foreground">
                        <tr>
                            <th class="px-4 py-2 text-left">Nº Pedido</th>
                            <th class="px-4 py-2 text-left">Fecha</th>
                            <th class="px-4 py-2 text-center">Estado</th>
                            <th class="px-4 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="order in orders"
                            :key="order.orderNumber"
                            class="hover:bg-muted/30 transition-colors cursor-pointer"
                            @click="router.visit(`/admin/orders/${order.orderNumber}`)"
                        >
                            <td class="px-4 py-2 font-mono text-xs font-semibold">{{ order.orderNumber }}</td>
                            <td class="px-4 py-2 text-muted-foreground">{{ order.orderDate }}</td>
                            <td class="px-4 py-2 text-center">
                                <span
                                    class="rounded-full px-2 py-0.5 text-[10px] font-medium"
                                    :class="{
                                        'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                        'bg-blue-100 text-blue-800':    order.status === 'processing',
                                        'bg-purple-100 text-purple-800': order.status === 'shipped',
                                        'bg-green-100 text-green-800':  order.status === 'delivered',
                                        'bg-red-100 text-red-800':      order.status === 'cancelled',
                                    }"
                                >
                                    {{ { pending: 'Pendiente', processing: 'En proceso', shipped: 'Enviado', delivered: 'Entregado', cancelled: 'Cancelado' }[order.status] ?? order.status }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right font-medium">{{ order.totalAmount.toFixed(2) }} €</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
