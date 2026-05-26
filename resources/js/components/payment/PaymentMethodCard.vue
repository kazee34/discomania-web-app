<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { CreditCard, Building2, Star, Trash2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

interface PaymentMethodCardData {
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

const props = defineProps<{ method: PaymentMethodCardData }>();

const deleteForm = useForm({});
const defaultForm = useForm({});

function remove() {
    if (!confirm('¿Eliminar este método de pago?')) return;
    deleteForm.delete(`/profile/payment-methods/${props.method.id}`, { preserveScroll: true });
}

function setDefault() {
    defaultForm.patch(`/profile/payment-methods/${props.method.id}/default`, { preserveScroll: true });
}

function brandLabel(brand: string) {
    return brand === 'visa' ? 'Visa' : 'Mastercard';
}

function expiryLabel(month: number, year: number) {
    return `${String(month).padStart(2, '0')}/${String(year).slice(-2)}`;
}
</script>

<template>
    <div
        class="flex items-center justify-between rounded-xl border p-4 transition-colors"
        :class="method.isDefault ? 'border-violet-400 bg-violet-50 dark:border-violet-600 dark:bg-violet-900/10' : 'bg-card'"
    >
        <div class="flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-muted">
                <CreditCard v-if="method.type === 'credit_card'" class="h-5 w-5 text-muted-foreground" />
                <Building2 v-else class="h-5 w-5 text-muted-foreground" />
            </div>

            <div>
                <p class="text-sm font-medium flex items-center gap-2">
                    {{ method.label }}
                    <span
                        v-if="method.isDefault"
                        class="inline-flex items-center gap-1 rounded-full bg-violet-100 px-2 py-0.5 text-xs font-medium text-violet-700 dark:bg-violet-900/30 dark:text-violet-300"
                    >
                        <Star class="h-3 w-3" /> Predeterminado
                    </span>
                </p>
                <p v-if="method.creditCard" class="text-xs text-muted-foreground mt-0.5">
                    {{ brandLabel(method.creditCard.cardBrand) }} · Caduca {{ expiryLabel(method.creditCard.cardExpiryMonth, method.creditCard.cardExpiryYear) }}
                </p>
                <p v-else-if="method.bankAccount" class="text-xs text-muted-foreground mt-0.5">
                    {{ method.bankAccount.ibanMasked }}
                    <span v-if="method.bankAccount.bankName"> · {{ method.bankAccount.bankName }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <Button
                v-if="!method.isDefault"
                variant="ghost"
                size="sm"
                :disabled="defaultForm.processing"
                @click="setDefault"
            >
                <Star class="h-4 w-4" />
                <span class="sr-only">Predeterminar</span>
            </Button>
            <Button
                variant="ghost"
                size="sm"
                class="text-destructive hover:text-destructive"
                :disabled="deleteForm.processing"
                @click="remove"
            >
                <Trash2 class="h-4 w-4" />
                <span class="sr-only">Eliminar</span>
            </Button>
        </div>
    </div>
</template>
