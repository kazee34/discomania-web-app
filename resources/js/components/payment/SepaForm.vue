<script setup lang="ts">
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps<{
    modelValue: {
        iban: string;
        account_holder: string;
        bic: string;
        bank_name: string;
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

function mod97(iban: string): number {
    const rearranged = iban.slice(4) + iban.slice(0, 4);
    let numeric = '';
    for (const ch of rearranged) {
        numeric += /[A-Z]/.test(ch) ? (ch.charCodeAt(0) - 55).toString() : ch;
    }
    let remainder = 0;
    for (let i = 0; i < numeric.length; i += 9) {
        remainder = parseInt(remainder.toString() + numeric.slice(i, i + 9), 10) % 97;
    }
    return remainder;
}

const ibanValidation = computed(() => {
    const raw = props.modelValue.iban.replace(/\s+/g, '').toUpperCase();
    if (!raw) return null;
    if (!/^ES\d{22}$/.test(raw)) return 'Solo se aceptan IBANs españoles (ES + 22 dígitos)';
    if (mod97(raw) !== 1) return 'Checksum incorrecto';
    return 'valid';
});

const maskedPreview = computed(() => {
    const raw = props.modelValue.iban.replace(/\s+/g, '').toUpperCase();
    if (raw.length < 8) return '';
    const first4  = raw.slice(0, 4);
    const last4   = raw.slice(-4);
    const middle  = '*'.repeat(raw.length - 8);
    const full    = first4 + middle + last4;
    return full.replace(/(.{4})/g, '$1 ').trim();
});
</script>

<template>
    <form class="flex flex-col gap-4" @submit.prevent="emit('submit')">
        <!-- IBAN -->
        <div>
            <Label for="iban">IBAN</Label>
            <Input
                id="iban"
                :value="modelValue.iban"
                placeholder="ES91 2100 0418 4502 0005 1332"
                class="mt-1 font-mono tracking-wider"
                @input="update('iban', ($event.target as HTMLInputElement).value)"
            />
            <div class="mt-1 flex items-center justify-between">
                <p
                    v-if="ibanValidation && ibanValidation !== 'valid'"
                    class="text-xs text-destructive"
                >{{ ibanValidation }}</p>
                <p v-else-if="ibanValidation === 'valid'" class="text-xs text-green-600">IBAN válido ✓</p>
                <p v-if="maskedPreview" class="text-xs text-muted-foreground ml-auto">{{ maskedPreview }}</p>
            </div>
            <p v-if="errors?.iban" class="mt-1 text-xs text-destructive">{{ errors.iban }}</p>
        </div>

        <!-- Titular -->
        <div>
            <Label for="account_holder">Titular de la cuenta</Label>
            <Input
                id="account_holder"
                :value="modelValue.account_holder"
                placeholder="Nombre completo del titular"
                class="mt-1"
                @input="update('account_holder', ($event.target as HTMLInputElement).value)"
            />
            <p v-if="errors?.account_holder" class="mt-1 text-xs text-destructive">{{ errors.account_holder }}</p>
        </div>

        <!-- BIC / SWIFT (opcional) -->
        <div>
            <Label for="bic">BIC / SWIFT <span class="text-muted-foreground text-xs">(opcional)</span></Label>
            <Input
                id="bic"
                :value="modelValue.bic"
                placeholder="CAIXESBBXXX"
                class="mt-1 font-mono"
                @input="update('bic', ($event.target as HTMLInputElement).value)"
            />
            <p v-if="errors?.bic" class="mt-1 text-xs text-destructive">{{ errors.bic }}</p>
        </div>

        <!-- Banco (opcional) -->
        <div>
            <Label for="bank_name">Nombre del banco <span class="text-muted-foreground text-xs">(opcional)</span></Label>
            <Input
                id="bank_name"
                :value="modelValue.bank_name"
                placeholder="CaixaBank, BBVA..."
                class="mt-1"
                @input="update('bank_name', ($event.target as HTMLInputElement).value)"
            />
        </div>

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
            {{ submitting ? 'Guardando...' : 'Guardar cuenta bancaria' }}
        </Button>
    </form>
</template>
