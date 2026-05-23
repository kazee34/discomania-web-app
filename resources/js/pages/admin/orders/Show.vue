<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem } from '@/types';

interface OrderItem {
    id: number;
    productSnapshot: {
        artist: string;
        album_title: string;
        cover_image_url: string | null;
    };
    quantity: number;
    pricePerUnit: number;
    subtotal: number;
}

interface Order {
    id: number;
    orderNumber: string;
    customerId: number;
    orderDate: string;
    subtotal: number;
    shippingCost: number;
    taxAmount: number;
    totalAmount: number;
    status: string;
    trackingNumber: string | null;
    customerNotes: string | null;
    adminNotes: string | null;
    items: OrderItem[];
}

const props = defineProps<{ order: Order }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Panel', href: '/admin/products' },
    { title: 'Pedidos', href: '/admin/orders' },
    { title: props.order.orderNumber },
];

const statusConfig: Record<string, { label: string; class: string }> = {
    pending:    { label: 'Pendiente',   class: 'bg-yellow-100 text-yellow-800' },
    processing: { label: 'En proceso',  class: 'bg-blue-100 text-blue-800' },
    shipped:    { label: 'Enviado',     class: 'bg-purple-100 text-purple-800' },
    delivered:  { label: 'Entregado',   class: 'bg-green-100 text-green-800' },
    cancelled:  { label: 'Cancelado',   class: 'bg-red-100 text-red-800' },
};

const transitions: Record<string, string[]> = {
    pending:    ['processing', 'cancelled'],
    processing: ['shipped', 'cancelled'],
    shipped:    ['delivered'],
    delivered:  [],
    cancelled:  [],
};

const statusForm = useForm({ status: '' });

function changeStatus(newStatus: string) {
    statusForm.status = newStatus;
    statusForm.patch(`/admin/orders/${props.order.orderNumber}/status`, { preserveScroll: true });
}
</script>

<template>
    <Head :title="`Pedido ${order.orderNumber} — Admin`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-6 max-w-3xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold font-mono">{{ order.orderNumber }}</h1>
                    <p class="text-sm text-muted-foreground mt-0.5">{{ order.orderDate }}</p>
                </div>
                <Button as-child variant="outline" size="sm">
                    <Link href="/admin/orders">Volver</Link>
                </Button>
            </div>

            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $page.props.flash.error }}
            </div>

            <!-- Estado y transiciones -->
            <div class="rounded-xl border bg-card p-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-muted-foreground">Estado actual:</span>
                    <span
                        class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                        :class="statusConfig[order.status]?.class"
                    >
                        {{ statusConfig[order.status]?.label ?? order.status }}
                    </span>
                </div>
                <div v-if="transitions[order.status]?.length" class="flex gap-2">
                    <Button
                        v-for="next in transitions[order.status]"
                        :key="next"
                        size="sm"
                        :variant="next === 'cancelled' ? 'destructive' : 'default'"
                        @click="changeStatus(next)"
                    >
                        → {{ statusConfig[next]?.label }}
                    </Button>
                </div>
            </div>

            <!-- Resumen económico -->
            <div class="rounded-xl border bg-card divide-y">
                <div class="grid grid-cols-2 gap-4 p-4">
                    <div>
                        <p class="text-xs text-muted-foreground">Subtotal</p>
                        <p class="font-medium mt-1">{{ order.subtotal.toFixed(2) }} €</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground">Envío</p>
                        <p class="font-medium mt-1">{{ order.shippingCost.toFixed(2) }} €</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 p-4">
                    <div>
                        <p class="text-xs text-muted-foreground">Impuestos</p>
                        <p class="font-medium mt-1">{{ order.taxAmount.toFixed(2) }} €</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground">Total</p>
                        <p class="font-bold text-lg mt-1">{{ order.totalAmount.toFixed(2) }} €</p>
                    </div>
                </div>
                <div v-if="order.trackingNumber" class="p-4">
                    <p class="text-xs text-muted-foreground">Nº seguimiento</p>
                    <p class="font-mono font-medium mt-1">{{ order.trackingNumber }}</p>
                </div>
                <div v-if="order.customerNotes" class="p-4">
                    <p class="text-xs text-muted-foreground">Notas del cliente</p>
                    <p class="text-sm mt-1">{{ order.customerNotes }}</p>
                </div>
            </div>

            <!-- Artículos -->
            <div class="rounded-xl border bg-card divide-y">
                <div
                    v-for="item in order.items"
                    :key="item.id"
                    class="flex items-center gap-4 p-4"
                >
                    <img
                        v-if="item.productSnapshot.cover_image_url"
                        :src="item.productSnapshot.cover_image_url"
                        :alt="item.productSnapshot.album_title"
                        class="h-12 w-12 rounded-md object-cover shrink-0"
                        loading="lazy"
                        decoding="async"
                    />
                    <div v-else class="h-12 w-12 rounded-md bg-muted shrink-0" />
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate">{{ item.productSnapshot.album_title }}</p>
                        <p class="text-sm text-muted-foreground truncate">{{ item.productSnapshot.artist }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-medium">{{ item.subtotal.toFixed(2) }} €</p>
                        <p class="text-xs text-muted-foreground">{{ item.pricePerUnit.toFixed(2) }} € x{{ item.quantity }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
