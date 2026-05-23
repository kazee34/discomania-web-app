<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import CartItemRow from '@/components/shop/CartItemRow.vue';
import CartSummary from '@/components/shop/CartSummary.vue';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
import { useCart } from '@/composables/useCart';

const { cart, itemCount, fetchCart } = useCart();

onMounted(() => fetchCart());
</script>

<template>
    <Head title="Carrito — Discomania" />

    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
            <h1 class="mb-8 text-3xl font-bold">Tu carrito</h1>

            <!-- Carrito con items -->
            <div v-if="cart && cart.items.length > 0" class="grid gap-8 lg:grid-cols-3">
                <!-- Lista de items -->
                <div class="lg:col-span-2">
                    <div class="rounded-xl border bg-card px-6">
                        <CartItemRow
                            v-for="item in cart.items"
                            :key="item.id"
                            :item="item"
                        />
                    </div>
                    <Link
                        href="/shop"
                        class="mt-4 inline-block text-sm text-muted-foreground hover:text-foreground"
                    >
                        ← Seguir comprando
                    </Link>
                </div>

                <!-- Resumen -->
                <div>
                    <CartSummary
                        :total-amount="cart.totalAmount"
                        :item-count="itemCount"
                    />
                </div>
            </div>

            <!-- Carrito vacío -->
            <div v-else class="py-24 text-center text-muted-foreground">
                <p class="mb-2 text-lg font-medium">Tu carrito está vacío</p>
                <p class="mb-6 text-sm">Añade algunos discos para empezar</p>
                <Link
                    href="/shop"
                    class="inline-flex items-center rounded-lg bg-primary px-5 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                >
                    Ir a la tienda
                </Link>
            </div>
        </main>
    </div>
</template>