<script setup lang="ts">
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps<{
    modelValue: {
        card_brand: string;
        card_number: string;
        card_holder: string;
        expiry_month: string;
        expiry_year: string;
        cvv: string;
        set_as_default: boolean;
    };
    errors?: Record<string, string>;
    submitting?: boolean;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: typeof props.modelValue];
    submit: [];
}>();

function update(field: string, value: string | boolean) {
    emit('update:modelValue', { ...props.modelValue, [field]: value });
}

function formatCardNumber(raw: string): string {
    return raw.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim();
}

function onCardNumberInput(e: Event) {
    const formatted = formatCardNumber((e.target as HTMLInputElement).value);
    (e.target as HTMLInputElement).value = formatted;
    update('card_number', formatted);
}

function luhn(number: string): boolean {
    const digits = number.replace(/\s/g, '');
    let sum = 0;
    let flip = false;
    for (let i = digits.length - 1; i >= 0; i--) {
        let d = parseInt(digits[i], 10);
        if (flip) { d *= 2; if (d > 9) d -= 9; }
        sum += d;
        flip = !flip;
    }
    return sum % 10 === 0;
}

const detectedBrand = computed(() => {
    const digits = props.modelValue.card_number.replace(/\s/g, '');
    if (digits.startsWith('4')) return 'visa';
    const p2 = parseInt(digits.slice(0, 2), 10);
    const p6 = parseInt(digits.slice(0, 6), 10);
    if ((p2 >= 51 && p2 <= 55) || (p6 >= 222100 && p6 <= 272099)) return 'mastercard';
    return null;
});

const luhnValid = computed(() => {
    const digits = props.modelValue.card_number.replace(/\s/g, '');
    return digits.length === 16 && luhn(digits);
});

const brandMismatch = computed(() =>
    props.modelValue.card_brand && detectedBrand.value && detectedBrand.value !== props.modelValue.card_brand
);

const currentYear = new Date().getFullYear();
const years = Array.from({ length: 15 }, (_, i) => currentYear + i);
</script>

<template>
    <form class="flex flex-col gap-4" @submit.prevent="emit('submit')">
        <!-- Marca (radio) -->
        <div>
            <Label class="mb-2 block">Tipo de tarjeta</Label>
            <div class="flex gap-4">
                <label class="flex cursor-pointer items-center gap-2">
                    <input
                        type="radio"
                        name="card_brand"
                        value="visa"
                        :checked="modelValue.card_brand === 'visa'"
                        class="accent-violet-600"
                        @change="update('card_brand', 'visa')"
                    />
                    <span class="text-sm font-medium">Visa</span>
                </label>
                <label class="flex cursor-pointer items-center gap-2">
                    <input
                        type="radio"
                        name="card_brand"
                        value="mastercard"
                        :checked="modelValue.card_brand === 'mastercard'"
                        class="accent-violet-600"
                        @change="update('card_brand', 'mastercard')"
                    />
                    <span class="text-sm font-medium">Mastercard</span>
                </label>
            </div>
            <p v-if="brandMismatch" class="mt-1 text-xs text-destructive">
                El número no corresponde a {{ modelValue.card_brand === 'visa' ? 'Visa' : 'Mastercard' }}.
            </p>
            <p v-if="errors?.card_brand" class="mt-1 text-xs text-destructive">{{ errors.card_brand }}</p>
        </div>

        <!-- Número de tarjeta -->
        <div>
            <Label for="card_number">Número de tarjeta</Label>
            <div class="relative mt-1">
                <Input
                    id="card_number"
                    :value="modelValue.card_number"
                    inputmode="numeric"
                    placeholder="0000 0000 0000 0000"
                    maxlength="19"
                    @input="onCardNumberInput"
                />
                <span
                    v-if="modelValue.card_number.replace(/\s/g, '').length === 16"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-xs"
                    :class="luhnValid ? 'text-green-600' : 'text-destructive'"
                >
                    {{ luhnValid ? '✓' : '✗' }}
                </span>
            </div>
            <p v-if="modelValue.card_number.replace(/\s/g, '').length === 16 && !luhnValid" class="mt-1 text-xs text-destructive">
                El número de tarjeta no es válido.
            </p>
            <p v-else-if="errors?.card_number" class="mt-1 text-xs text-destructive">{{ errors.card_number }}</p>
        </div>

        <!-- Titular -->
        <div>
            <Label for="card_holder">Titular</Label>
            <Input
                id="card_holder"
                :value="modelValue.card_holder"
                placeholder="Nombre tal como aparece en la tarjeta"
                class="mt-1"
                @input="update('card_holder', ($event.target as HTMLInputElement).value)"
            />
            <p v-if="errors?.card_holder" class="mt-1 text-xs text-destructive">{{ errors.card_holder }}</p>
        </div>

        <!-- Caducidad + CVV -->
        <div class="grid grid-cols-3 gap-3">
            <div>
                <Label for="expiry_month">Mes</Label>
                <select
                    id="expiry_month"
                    :value="modelValue.expiry_month"
                    class="mt-1 w-full rounded-md border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"
                    @change="update('expiry_month', ($event.target as HTMLSelectElement).value)"
                >
                    <option value="">MM</option>
                    <option v-for="m in 12" :key="m" :value="m">{{ String(m).padStart(2, '0') }}</option>
                </select>
            </div>
            <div>
                <Label for="expiry_year">Año</Label>
                <select
                    id="expiry_year"
                    :value="modelValue.expiry_year"
                    class="mt-1 w-full rounded-md border bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"
                    @change="update('expiry_year', ($event.target as HTMLSelectElement).value)"
                >
                    <option value="">AAAA</option>
                    <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                </select>
            </div>
            <div>
                <Label for="cvv">CVV <span class="text-destructive">*</span></Label>
                <Input
                    id="cvv"
                    :value="modelValue.cvv"
                    inputmode="numeric"
                    placeholder="123"
                    maxlength="3"
                    class="mt-1"
                    @input="update('cvv', ($event.target as HTMLInputElement).value.replace(/\D/g, '').slice(0, 3))"
                />
                <p v-if="errors?.cvv" class="mt-1 text-xs text-destructive">{{ errors.cvv }}</p>
            </div>
        </div>
        <p v-if="errors?.expiry_month || errors?.expiry_year" class="text-xs text-destructive">
            {{ errors.expiry_month ?? errors.expiry_year }}
        </p>

        <!-- Predeterminar -->
        <label class="flex items-center gap-2 text-sm">
            <input
                type="checkbox"
                :checked="modelValue.set_as_default"
                class="accent-violet-600"
                @change="update('set_as_default', ($event.target as HTMLInputElement).checked)"
            />
            Usar como método predeterminado
        </label>

        <Button type="submit" :disabled="submitting">
            {{ submitting ? 'Guardando...' : 'Guardar tarjeta' }}
        </Button>
    </form>
</template>
