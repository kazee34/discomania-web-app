<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ShieldBan, ShieldCheck } from 'lucide-vue-next';
import { onUnmounted, ref } from 'vue';
import ProfileLayout from '@/components/profile/ProfileLayout.vue';
import TwoFactorRecoveryCodes from '@/components/TwoFactorRecoveryCodes.vue';
import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import { disable, enable } from '@/routes/two-factor';

type Props = {
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
};

withDefaults(defineProps<Props>(), {
    requiresConfirmation: false,
    twoFactorEnabled: false,
});

const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth();
const showSetupModal = ref<boolean>(false);

onUnmounted(() => {
    clearTwoFactorAuthData();
});
</script>

<template>
    <Head title="Verificación en 2 pasos" />

    <ProfileLayout>
        <div class="flex flex-col gap-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Verificación en 2 pasos</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Añade una capa extra de seguridad a tu cuenta.
                    </p>
                </div>
                <Link
                    href="/profile"
                    class="inline-flex items-center rounded-md border px-3 py-1.5 text-sm font-medium transition-colors hover:bg-muted"
                >
                    Volver al perfil
                </Link>
            </div>

            <section class="rounded-xl border bg-card p-5">
                <div v-if="!twoFactorEnabled" class="flex flex-col items-start gap-4">
                    <Badge variant="destructive">Desactivada</Badge>
                    <p class="text-sm text-muted-foreground">
                        Al activar la verificación en 2 pasos, se te pedirá un código seguro al iniciar sesión.
                        Puedes obtenerlo desde cualquier aplicación TOTP en tu móvil.
                    </p>
                    <div>
                        <Button v-if="hasSetupData" @click="showSetupModal = true">
                            <ShieldCheck class="mr-2 h-4 w-4" /> Continuar configuración
                        </Button>
                        <Form
                            v-else
                            v-bind="enable.form()"
                            @success="showSetupModal = true"
                            #default="{ processing }"
                        >
                            <Button type="submit" :disabled="processing">
                                <ShieldCheck class="mr-2 h-4 w-4" /> Activar 2FA
                            </Button>
                        </Form>
                    </div>
                </div>

                <div v-else class="flex flex-col items-start gap-4">
                    <Badge variant="default">Activada</Badge>
                    <p class="text-sm text-muted-foreground">
                        Con la verificación en 2 pasos activada, se te pedirá un código aleatorio al iniciar sesión,
                        que podrás obtener desde tu aplicación TOTP.
                    </p>
                    <TwoFactorRecoveryCodes />
                    <Form v-bind="disable.form()" #default="{ processing }">
                        <Button variant="destructive" type="submit" :disabled="processing">
                            <ShieldBan class="mr-2 h-4 w-4" /> Desactivar 2FA
                        </Button>
                    </Form>
                </div>
            </section>
        </div>

        <TwoFactorSetupModal
            v-model:isOpen="showSetupModal"
            :requiresConfirmation="requiresConfirmation"
            :twoFactorEnabled="twoFactorEnabled"
        />
    </ProfileLayout>
</template>
