<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import ProfileLayout from '@/components/profile/ProfileLayout.vue';
import { Button } from '@/components/ui/button';

interface OrderItem {
    id: number;
    productId: number;
    productSnapshot: {
        artist: string;
        album_title: string;
        slug: string;
        cover_image_url: string | null;
    };
    quantity: number;
    pricePerUnit: number;
    subtotal: number;
}

interface Order {
    orderNumber: string;
    orderDate: string;
    subtotal: number;
    shippingCost: number;
    taxAmount: number;
    totalAmount: number;
    status: string;
    trackingNumber: string | null;
    customerNotes: string | null;
    items: OrderItem[];
}

const props = defineProps<{ order: Order }>();

const cancelForm = useForm({});

function cancelOrder() {
    cancelForm.delete(`/profile/orders/${props.order.orderNumber}`);
}

const statusConfig: Record<string, { label: string; class: string }> = {
    pending:    { label: 'Pendiente',   class: 'bg-yellow-100 text-yellow-800' },
    processing: { label: 'En proceso',  class: 'bg-blue-100 text-blue-800' },
    shipped:    { label: 'Enviado',     class: 'bg-purple-100 text-purple-800' },
    delivered:  { label: 'Entregado',   class: 'bg-green-100 text-green-800' },
    cancelled:  { label: 'Cancelado',   class: 'bg-red-100 text-red-800' },
};

function formatDate(iso: string) {
    return new Date(iso).toLocaleDateString('es-ES', { dateStyle: 'long' });
}
</script>

<template>
    <Head :title="`Pedido ${order.orderNumber} — Discomania`" />

    <ProfileLayout>
        <div class="flex flex-col gap-6">
            <!-- Header -->
            <div class="flex items-start justify-between gap-4">
                <div>
                    <Link href="/profile/orders" class="text-xs text-muted-foreground hover:text-foreground">
                        ← Mis pedidos
                    </Link>
                    <h1 class="mt-1 text-2xl font-bold font-mono">{{ order.orderNumber }}</h1>
                    <p class="text-sm text-muted-foreground">{{ formatDate(order.orderDate) }}</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span
                        class="rounded-full px-3 py-1 text-xs font-medium"
                        :class="statusConfig[order.status]?.class ?? 'bg-muted text-muted-foreground'"
                    >
                        {{ statusConfig[order.status]?.label ?? order.status }}
                    </span>
                    <Button
                        v-if="order.status === 'pending'"
                        variant="destructive"
                        size="sm"
                        :disabled="cancelForm.processing"
                        @click="cancelOrder"
                    >
                        Cancelar pedido
                    </Button>
                </div>
            </div>

            <!-- Flash messages -->
            <div v-if="$page.props.flash?.success" class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $page.props.flash.error }}
            </div>

            <!-- Items -->
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
                        class="h-14 w-14 rounded-md object-cover shrink-0"
                    />
                    <div v-else class="h-14 w-14 rounded-md bg-muted shrink-0" />
                    <div class="flex-1 min-w-0">
                        <Link
                            :href="`/shop/${item.productSnapshot.slug}`"
                            class="font-medium truncate hover:underline block"
                        >
                            {{ item.productSnapshot.album_title }}
                        </Link>
                        <p class="text-sm text-muted-foreground truncate">{{ item.productSnapshot.artist }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-sm font-medium">{{ item.subtotal.toFixed(2) }} €</p>
                        <p class="text-xs text-muted-foreground">{{ item.pricePerUnit.toFixed(2) }} € × {{ item.quantity }}</p>
                    </div>
                </div>
            </div>

            <!-- Totals -->
            <div class="rounded-xl border bg-card p-5 flex flex-col gap-2">
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Subtotal</span>
                    <span>{{ order.subtotal.toFixed(2) }} €</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Envío</span>
                    <span :class="order.shippingCost === 0 ? 'text-green-600' : ''">
                        {{ order.shippingCost === 0 ? 'Gratis' : order.shippingCost.toFixed(2) + ' €' }}
                    </span>
                </div>
                <div v-if="order.taxAmount > 0" class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Impuestos</span>
                    <span>{{ order.taxAmount.toFixed(2) }} €</span>
                </div>
                <div class="border-t pt-2 flex justify-between font-bold">
                    <span>Total</span>
                    <span>{{ order.totalAmount.toFixed(2) }} €</span>
                </div>
            </div>

            <!-- Tracking / Notes -->
            <div v-if="order.trackingNumber || order.customerNotes" class="rounded-xl border bg-card p-5 flex flex-col gap-3">
                <div v-if="order.trackingNumber" class="flex justify-between text-sm">
                    <span class="text-muted-foreground">Número de seguimiento</span>
                    <span class="font-mono font-medium">{{ order.trackingNumber }}</span>
                </div>
                <div v-if="order.customerNotes" class="flex flex-col gap-1">
                    <span class="text-xs text-muted-foreground uppercase tracking-wide">Notas</span>
                    <p class="text-sm">{{ order.customerNotes }}</p>
                </div>
            </div>
        </div>
    </ProfileLayout>
</template>