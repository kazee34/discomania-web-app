<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ProfileLayout from '@/components/profile/ProfileLayout.vue';

interface Order {
    orderNumber: string;
    orderDate: string;
    totalAmount: number;
    status: string;
    itemCount: number;
}

defineProps<{ orders: Order[] }>();

const statusConfig: Record<string, { label: string; class: string }> = {
    pending:    { label: 'Pendiente',   class: 'bg-yellow-100 text-yellow-800' },
    processing: { label: 'En proceso',  class: 'bg-blue-100 text-blue-800' },
    shipped:    { label: 'Enviado',     class: 'bg-purple-100 text-purple-800' },
    delivered:  { label: 'Entregado',   class: 'bg-green-100 text-green-800' },
    cancelled:  { label: 'Cancelado',   class: 'bg-red-100 text-red-800' },
};

function formatDate(iso: string) {
    return new Date(iso).toLocaleDateString('es-ES', { dateStyle: 'medium' });
}
</script>

<template>
    <Head title="Mis pedidos — Discomania" />

    <ProfileLayout>
        <div class="flex flex-col gap-6">
            <div>
                <h1 class="text-2xl font-bold">Mis pedidos</h1>
                <p class="text-sm text-muted-foreground mt-1">Historial completo de tus compras.</p>
            </div>

            <div v-if="orders.length > 0" class="flex flex-col gap-3">
                <Link
                    v-for="order in orders"
                    :key="order.orderNumber"
                    :href="`/profile/orders/${order.orderNumber}`"
                    class="rounded-xl border bg-card p-4 flex items-center justify-between gap-4 hover:bg-muted/50 transition-colors"
                >
                    <div class="flex flex-col gap-1 min-w-0">
                        <span class="font-mono text-sm font-semibold">{{ order.orderNumber }}</span>
                        <span class="text-xs text-muted-foreground">
                            {{ formatDate(order.orderDate) }} · {{ order.itemCount }} {{ order.itemCount === 1 ? 'artículo' : 'artículos' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span
                            class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                            :class="statusConfig[order.status]?.class ?? 'bg-muted text-muted-foreground'"
                        >
                            {{ statusConfig[order.status]?.label ?? order.status }}
                        </span>
                        <span class="text-sm font-bold">{{ order.totalAmount.toFixed(2) }} €</span>
                        <span class="text-muted-foreground text-sm">→</span>
                    </div>
                </Link>
            </div>

            <div v-else class="py-16 text-center text-muted-foreground">
                <p class="font-medium">Todavía no tienes pedidos</p>
                <Link href="/shop" class="mt-4 inline-block text-sm text-primary hover:underline">
                    Ir a la tienda
                </Link>
            </div>
        </div>
    </ProfileLayout>
</template>