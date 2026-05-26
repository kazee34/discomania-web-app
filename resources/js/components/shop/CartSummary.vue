<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { login, register } from '@/routes';

const props = defineProps<{
    subtotal: number;
    itemCount: number;
}>();

const TAX_RATE = 0.21;
const SHIPPING_THRESHOLD = 60;
const SHIPPING_COST = 6.99;

const iva      = computed(() => Math.round(props.subtotal * TAX_RATE * 100) / 100);
const shipping = computed(() => props.subtotal >= SHIPPING_THRESHOLD ? 0 : SHIPPING_COST);
const total    = computed(() => Math.round((props.subtotal + iva.value + shipping.value) * 100) / 100);

function checkout() {
    const cartToken = localStorage.getItem('discomania_cart_token') ?? '';
    router.visit(`/checkout?cart_token=${encodeURIComponent(cartToken)}`);
}
</script>

<template>
    <div class="rounded-xl border bg-card p-6 flex flex-col gap-4">
        <h2 class="text-lg font-semibold">Resumen</h2>

        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground">Subtotal ({{ itemCount }} {{ itemCount === 1 ? 'artículo' : 'artículos' }})</span>
            <span class="font-medium">{{ subtotal.toFixed(2) }} €</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground">IVA (21%)</span>
            <span class="font-medium">{{ iva.toFixed(2) }} €</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground">Envío</span>
            <span :class="shipping === 0 ? 'text-green-600 font-medium' : 'font-medium'">
                {{ shipping === 0 ? 'Gratis' : shipping.toFixed(2) + ' €' }}
            </span>
        </div>
        <p v-if="shipping > 0" class="text-xs text-muted-foreground -mt-2">
            Envío gratis a partir de {{ SHIPPING_THRESHOLD }} €
        </p>

        <div class="border-t pt-4 flex justify-between font-bold">
            <span>Total</span>
            <span>{{ total.toFixed(2) }} €</span>
        </div>

        <template v-if="$page.props.auth.user">
            <Button class="w-full" size="lg" @click="checkout">
                Finalizar compra
            </Button>
        </template>
        <template v-else>
            <Button as-child class="w-full" size="lg">
                <Link :href="login()">Iniciar sesión</Link>
            </Button>
            <Button as-child class="w-full" variant="outline" size="lg">
                <Link :href="register()">Crear cuenta</Link>
            </Button>
            <p class="text-center text-xs text-muted-foreground">
                Necesitas una cuenta para completar el pedido
            </p>
        </template>
    </div>
</template>