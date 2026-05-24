<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import InputError from '@/components/InputError.vue';
import ProfileLayout from '@/components/profile/ProfileLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
</script>

<template>
    <Head title="Contraseña" />

    <ProfileLayout>
        <div class="flex flex-col gap-8">
            <div>
                <h1 class="text-2xl font-bold">Contraseña</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Usa una contraseña larga y aleatoria para mantener tu cuenta segura.
                </p>
            </div>

            <section class="rounded-xl border bg-card p-5">
                <Form
                    v-bind="PasswordController.update.form()"
                    :options="{ preserveScroll: true }"
                    reset-on-success
                    :reset-on-error="['password', 'password_confirmation', 'current_password']"
                    class="flex flex-col gap-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="grid gap-2">
                        <Label for="current_password">Contraseña actual</Label>
                        <Input
                            id="current_password"
                            name="current_password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="current-password"
                            placeholder="Contraseña actual"
                        />
                        <InputError :message="errors.current_password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Nueva contraseña</Label>
                        <Input
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                            placeholder="Nueva contraseña"
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirmar contraseña</Label>
                        <Input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                            placeholder="Confirmar contraseña"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing" data-test="update-password-button">
                            {{ processing ? 'Guardando...' : 'Guardar contraseña' }}
                        </Button>
                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="recentlySuccessful" class="text-sm text-green-600">
                                Guardado.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </section>
        </div>
    </ProfileLayout>
</template>
