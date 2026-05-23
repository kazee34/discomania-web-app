<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
import { useCart } from '@/composables/useCart';

interface OrderItem {
    id: number;
    productId: number;
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
    orderDate: string;
    totalAmount: number;
    status: string;
    customerNotes: string | null;
    items: OrderItem[];
}

defineProps<{ order: Order }>();

const { cart } = useCart();

onMounted(() => {
    localStorage.removeItem('discomania_cart_token');
    cart.value = null;
});
</script>

<template>
    <Head title="Pedido confirmado — Discomania" />

    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <main class="mx-auto max-w-2xl px-4 py-16 sm:px-6">
            <div class="mb-10 text-center">
                <h1 class="mb-2 text-3xl font-bold">Pedido confirmado</h1>
                <p class="text-muted-foreground">
                    Gracias por tu compra. Hemos recibido tu pedido.
                </p>
            </div>

            <div class="rounded-xl border bg-card p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-muted-foreground">Número de pedido</span>
                    <span class="font-mono font-semibold">{{ order.orderNumber }}</span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-muted-foreground">Fecha</span>
                    <span class="text-sm">{{ new Date(order.orderDate).toLocaleDateString('es-ES', { dateStyle: 'long' }) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-muted-foreground">Estado</span>
                    <span class="rounded-full bg-green-100 px-3 py-0.5 text-xs font-medium text-green-800 capitalize">
                        {{ order.status }}
                    </span>
                </div>
            </div>

            <div class="rounded-xl border bg-card divide-y mb-6">
                <div
                    v-for="item in order.items"
                    :key="item.id"
                    class="flex items-center gap-4 p-4"
                >
                    <img
                        v-if="item.productSnapshot.cover_image_url"
                        :src="item.productSnapshot.cover_image_url"
                        :alt="item.productSnapshot.album_title"
                        class="h-14 w-14 rounded-md object-cover"
                    />
                    <div v-else class="h-14 w-14 rounded-md bg-muted" />
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate">{{ item.productSnapshot.album_title }}</p>
                        <p class="text-sm text-muted-foreground truncate">{{ item.productSnapshot.artist }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium">{{ item.subtotal.toFixed(2) }} €</p>
                        <p class="text-xs text-muted-foreground">x{{ item.quantity }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border bg-card p-6 mb-8">
                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>{{ order.totalAmount.toFixed(2) }} €</span>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <Link
                    href="/shop"
                    class="flex-1 inline-flex items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    Seguir comprando
                </Link>
                <Link
                    href="/dashboard"
                    class="flex-1 inline-flex items-center justify-center rounded-lg border px-5 py-2.5 text-sm font-medium hover:bg-muted"
                >
                    Ir al dashboard
                </Link>
            </div>
        </main>
    </div>
</template>