<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { login, register } from '@/routes';

const props = defineProps<{
    totalAmount: number;
    itemCount: number;
}>();

const form = useForm({
    cart_token: localStorage.getItem('discomania_cart_token') ?? '',
    customer_notes: '',
});

function checkout() {
    form.post('/checkout');
}
</script>

<template>
    <div class="rounded-xl border bg-card p-6 flex flex-col gap-4">
        <h2 class="text-lg font-semibold">Resumen</h2>

        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground">Subtotal ({{ itemCount }} artículos)</span>
            <span class="font-medium">{{ totalAmount.toFixed(2) }} €</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-muted-foreground">Envío</span>
            <span class="text-green-600 font-medium">Gratis</span>
        </div>

        <div class="border-t pt-4 flex justify-between font-bold">
            <span>Total</span>
            <span>{{ totalAmount.toFixed(2) }} €</span>
        </div>

        <template v-if="$page.props.auth.user">
            <Button class="w-full" size="lg" :disabled="form.processing" @click="checkout">
                {{ form.processing ? 'Procesando...' : 'Finalizar compra' }}
            </Button>
            <p v-if="form.errors.cart_token" class="text-center text-xs text-destructive">
                {{ form.errors.cart_token }}
            </p>
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