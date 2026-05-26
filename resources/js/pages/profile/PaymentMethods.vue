<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { CreditCard, Building2, Plus } from 'lucide-vue-next';
import ProfileLayout from '@/components/profile/ProfileLayout.vue';
import PaymentMethodCard from '@/components/payment/PaymentMethodCard.vue';
import CreditCardForm from '@/components/payment/CreditCardForm.vue';
import SepaForm from '@/components/payment/SepaForm.vue';
import { Button } from '@/components/ui/button';

interface PaymentMethod {
    id: number;
    type: 'credit_card' | 'sepa_debit';
    isDefault: boolean;
    label: string;
    creditCard?: {
        cardBrand: string;
        cardLastFour: string;
        cardExpiryMonth: number;
        cardExpiryYear: number;
    };
    bankAccount?: {
        ibanMasked: string;
        bankName: string | null;
    };
}

defineProps<{ paymentMethods: PaymentMethod[] }>();

type Panel = null | 'credit_card' | 'sepa_debit';
const openPanel = ref<Panel>(null);

const cardForm = useForm({
    card_brand: 'visa',
    card_number: '',
    card_holder: '',
    expiry_month: '',
    expiry_year: '',
    cvv: '',
    set_as_default: false,
});

const sepaForm = useForm({
    iban: '',
    account_holder: '',
    bic: '',
    bank_name: '',
    set_as_default: false,
});

function submitCard() {
    cardForm.post('/profile/payment-methods/credit-card', {
        onSuccess: () => { cardForm.reset(); openPanel.value = null; },
    });
}

function submitSepa() {
    sepaForm.post('/profile/payment-methods/bank-account', {
        onSuccess: () => { sepaForm.reset(); openPanel.value = null; },
    });
}
</script>

<template>
    <Head title="Métodos de pago" />
    <ProfileLayout>
        <div class="flex flex-col gap-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Métodos de pago</h1>
                    <p class="text-sm text-muted-foreground mt-1">
                        Gestiona tus tarjetas y cuentas bancarias guardadas.
                    </p>
                </div>
            </div>

            <!-- Lista de métodos -->
            <div v-if="paymentMethods.length > 0" class="flex flex-col gap-3">
                <PaymentMethodCard
                    v-for="method in paymentMethods"
                    :key="method.id"
                    :method="method"
                />
            </div>
            <div v-else class="rounded-xl border border-dashed p-8 text-center text-muted-foreground text-sm">
                No tienes métodos de pago guardados aún.
            </div>

            <!-- Botones para añadir -->
            <div v-if="openPanel === null" class="flex flex-wrap gap-3">
                <Button variant="outline" @click="openPanel = 'credit_card'">
                    <CreditCard class="mr-2 h-4 w-4" />
                    Añadir tarjeta
                </Button>
                <Button variant="outline" @click="openPanel = 'sepa_debit'">
                    <Building2 class="mr-2 h-4 w-4" />
                    Añadir cuenta bancaria (SEPA)
                </Button>
            </div>

            <!-- Formulario tarjeta -->
            <div v-if="openPanel === 'credit_card'" class="rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold flex items-center gap-2">
                        <CreditCard class="h-4 w-4" /> Nueva tarjeta
                    </h2>
                    <Button variant="ghost" size="sm" @click="openPanel = null">Cancelar</Button>
                </div>
                <CreditCardForm
                    v-model="cardForm"
                    :errors="cardForm.errors"
                    :submitting="cardForm.processing"
                    @submit="submitCard"
                />
            </div>

            <!-- Formulario SEPA -->
            <div v-if="openPanel === 'sepa_debit'" class="rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold flex items-center gap-2">
                        <Building2 class="h-4 w-4" /> Nueva cuenta bancaria (SEPA)
                    </h2>
                    <Button variant="ghost" size="sm" @click="openPanel = null">Cancelar</Button>
                </div>
                <SepaForm
                    v-model="sepaForm"
                    :errors="sepaForm.errors"
                    :submitting="sepaForm.processing"
                    @submit="submitSepa"
                />
            </div>
        </div>
    </ProfileLayout>
</template>
