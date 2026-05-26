<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { CreditCard, Building2, ChevronLeft } from 'lucide-vue-next';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
import CreditCardForm from '@/components/payment/CreditCardForm.vue';
import SepaForm from '@/components/payment/SepaForm.vue';
import { Button } from '@/components/ui/button';

interface SavedMethod {
    id: number;
    type: 'credit_card' | 'sepa_debit';
    isDefault: boolean;
    label: string;
}

const props = defineProps<{
    cartToken: string;
    savedMethods: SavedMethod[];
}>();

// Payment selection state
type PaymentMode = 'saved' | 'new_card' | 'new_sepa';
const paymentMode = ref<PaymentMode>(
    props.savedMethods.length > 0 ? 'saved' : 'new_card'
);
const selectedMethodId = ref<number | null>(
    props.savedMethods.find(m => m.isDefault)?.id ?? props.savedMethods[0]?.id ?? null
);
const customerNotes = ref('');

const cardData = ref({
    card_brand: 'visa',
    card_number: '',
    card_holder: '',
    expiry_month: '',
    expiry_year: '',
    cvv: '',
    set_as_default: false,
});

const sepaData = ref({
    iban: '',
    account_holder: '',
    bic: '',
    bank_name: '',
    set_as_default: false,
});

const form = useForm<Record<string, unknown>>({});

const isSubmitting = computed(() => form.processing);

function submit() {
    const payload: Record<string, unknown> = {
        cart_token: props.cartToken,
        customer_notes: customerNotes.value || null,
    };

    if (paymentMode.value === 'saved' && selectedMethodId.value !== null) {
        payload.payment_method_id = selectedMethodId.value;
    } else if (paymentMode.value === 'new_card') {
        payload.payment_type  = 'credit_card';
        payload.card_number   = cardData.value.card_number;
        payload.card_brand    = cardData.value.card_brand;
        payload.card_holder   = cardData.value.card_holder;
        payload.expiry_month  = cardData.value.expiry_month;
        payload.expiry_year   = cardData.value.expiry_year;
        payload.cvv           = cardData.value.cvv;
    } else {
        payload.payment_type    = 'sepa_debit';
        payload.iban            = sepaData.value.iban;
        payload.account_holder  = sepaData.value.account_holder;
        payload.bic             = sepaData.value.bic || null;
        payload.bank_name       = sepaData.value.bank_name || null;
    }

    form.transform(() => payload).post('/checkout');
}

function goBack() {
    router.visit('/cart');
}
</script>

<template>
    <Head title="Finalizar compra" />
    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6">
            <button class="mb-6 flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground" @click="goBack">
                <ChevronLeft class="h-4 w-4" /> Volver al carrito
            </button>

            <h1 class="text-2xl font-bold mb-8">Finalizar compra</h1>

            <form class="grid gap-8 md:grid-cols-[1fr_340px]" @submit.prevent="submit">
                <!-- Panel pago -->
                <div class="flex flex-col gap-6">
                    <div class="rounded-xl border p-5">
                        <h2 class="text-base font-semibold mb-4">Método de pago</h2>

                        <!-- Métodos guardados -->
                        <div v-if="savedMethods.length > 0" class="flex flex-col gap-3 mb-4">
                            <label
                                v-for="method in savedMethods"
                                :key="method.id"
                                class="flex cursor-pointer items-center gap-3 rounded-lg border p-3 transition-colors"
                                :class="paymentMode === 'saved' && selectedMethodId === method.id
                                    ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/10'
                                    : 'hover:bg-muted'"
                            >
                                <input
                                    type="radio"
                                    name="payment_method"
                                    :value="method.id"
                                    :checked="paymentMode === 'saved' && selectedMethodId === method.id"
                                    class="accent-violet-600"
                                    @change="() => { paymentMode = 'saved'; selectedMethodId = method.id; }"
                                />
                                <CreditCard v-if="method.type === 'credit_card'" class="h-4 w-4 text-muted-foreground shrink-0" />
                                <Building2 v-else class="h-4 w-4 text-muted-foreground shrink-0" />
                                <span class="text-sm font-medium">{{ method.label }}</span>
                                <span v-if="method.isDefault" class="ml-auto text-xs text-violet-600 font-medium">Predeterminado</span>
                            </label>
                        </div>

                        <!-- Opciones nueva tarjeta / SEPA -->
                        <div class="flex flex-col gap-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg border p-3 transition-colors"
                                :class="paymentMode === 'new_card' ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/10' : 'hover:bg-muted'">
                                <input type="radio" name="payment_method" value="new_card"
                                    :checked="paymentMode === 'new_card'"
                                    class="accent-violet-600"
                                    @change="paymentMode = 'new_card'" />
                                <CreditCard class="h-4 w-4 text-muted-foreground shrink-0" />
                                <span class="text-sm font-medium">Nueva tarjeta (Visa / Mastercard)</span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg border p-3 transition-colors"
                                :class="paymentMode === 'new_sepa' ? 'border-violet-500 bg-violet-50 dark:bg-violet-900/10' : 'hover:bg-muted'">
                                <input type="radio" name="payment_method" value="new_sepa"
                                    :checked="paymentMode === 'new_sepa'"
                                    class="accent-violet-600"
                                    @change="paymentMode = 'new_sepa'" />
                                <Building2 class="h-4 w-4 text-muted-foreground shrink-0" />
                                <span class="text-sm font-medium">Débito SEPA (IBAN)</span>
                            </label>
                        </div>

                        <!-- Formulario inline tarjeta -->
                        <div v-if="paymentMode === 'new_card'" class="mt-5 border-t pt-5">
                            <CreditCardForm
                                v-model="cardData"
                                :errors="form.errors"
                                :submitting="form.processing"
                                @submit="submit"
                            />
                        </div>

                        <!-- Formulario inline SEPA -->
                        <div v-if="paymentMode === 'new_sepa'" class="mt-5 border-t pt-5">
                            <SepaForm
                                v-model="sepaData"
                                :errors="form.errors"
                                :submitting="form.processing"
                                @submit="submit"
                            />
                        </div>
                    </div>

                    <!-- Notas del pedido -->
                    <div class="rounded-xl border p-5">
                        <h2 class="text-base font-semibold mb-3">Notas del pedido <span class="text-muted-foreground text-sm font-normal">(opcional)</span></h2>
                        <textarea
                            v-model="customerNotes"
                            rows="3"
                            maxlength="500"
                            placeholder="Instrucciones de entrega, preferencias..."
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-violet-500"
                        />
                    </div>
                </div>

                <!-- Resumen + botón -->
                <div class="flex flex-col gap-4">
                    <div class="rounded-xl border bg-card p-5 sticky top-6">
                        <h2 class="text-base font-semibold mb-4">Resumen</h2>
                        <p class="text-sm text-muted-foreground mb-6">
                            Revisa tu carrito antes de confirmar el pedido.
                        </p>

                        <div v-if="form.errors.cart_token" class="mb-4 rounded-md bg-destructive/10 p-3 text-xs text-destructive">
                            {{ form.errors.cart_token }}
                        </div>
                        <div v-if="form.errors.payment_method_id || form.errors.payment_type" class="mb-4 rounded-md bg-destructive/10 p-3 text-xs text-destructive">
                            {{ form.errors.payment_method_id ?? form.errors.payment_type }}
                        </div>

                        <Button
                            type="submit"
                            class="w-full"
                            size="lg"
                            :disabled="isSubmitting"
                            @click.prevent="submit"
                        >
                            {{ isSubmitting ? 'Procesando...' : 'Confirmar pedido' }}
                        </Button>
                        <p class="text-center text-xs text-muted-foreground mt-3">
                            Este es un pago simulado. No se realizará ningún cobro real.
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
