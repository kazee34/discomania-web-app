<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import InputPassword from '@/components/ui/input-password.vue';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
</script>

<template>
    <AuthBase
        title="Crea tu cuenta"
        description="Introduce tus datos para registrarte en Discomania"
        max-width="max-w-xl"
    >
        <Head title="Registro" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-5">
                <!-- Nombre y apellidos -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="first_name">Nombre</Label>
                        <Input
                            id="first_name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="given-name"
                            name="first_name"
                            placeholder="María"
                        />
                        <InputError :message="errors.first_name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="last_name">Apellidos</Label>
                        <Input
                            id="last_name"
                            type="text"
                            required
                            :tabindex="2"
                            autocomplete="family-name"
                            name="last_name"
                            placeholder="García López"
                        />
                        <InputError :message="errors.last_name" />
                    </div>
                </div>

                <!-- Correo electrónico -->
                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="3"
                        autocomplete="email"
                        name="email"
                        placeholder="correo@ejemplo.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <!-- Contraseña -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="password">Contraseña</Label>
                        <InputPassword
                            id="password"
                            required
                            :tabindex="4"
                            autocomplete="new-password"
                            name="password"
                            placeholder="••••••••"
                        />
                        <InputError :message="errors.password" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirmar contraseña</Label>
                        <InputPassword
                            id="password_confirmation"
                            required
                            :tabindex="5"
                            autocomplete="new-password"
                            name="password_confirmation"
                            placeholder="••••••••"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>
                </div>

                <!-- Teléfono y fecha de nacimiento -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="phone">Teléfono</Label>
                        <Input
                            id="phone"
                            type="tel"
                            :tabindex="6"
                            autocomplete="tel"
                            name="phone"
                            placeholder="+34 600 000 000"
                        />
                        <InputError :message="errors.phone" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="birth_date">Fecha de nacimiento</Label>
                        <Input
                            id="birth_date"
                            type="date"
                            :tabindex="7"
                            name="birth_date"
                            min="1900-01-01"
                        />
                        <InputError :message="errors.birth_date" />
                    </div>
                </div>

                <!-- DNI/NIF -->
                <div class="grid gap-2">
                    <Label for="dni_nif">
                        DNI / NIF
                        <span class="text-xs text-muted-foreground">(opcional)</span>
                    </Label>
                    <Input
                        id="dni_nif"
                        type="text"
                        :tabindex="8"
                        name="dni_nif"
                        placeholder="12345678A"
                    />
                    <InputError :message="errors.dni_nif" />
                </div>

                <!-- Dirección de envío -->
                <div class="space-y-4 rounded-lg border p-4">
                    <p class="text-sm font-semibold">Dirección de envío</p>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2 grid gap-2">
                            <Label for="shipping_street">Calle</Label>
                            <Input
                                id="shipping_street"
                                type="text"
                                required
                                :tabindex="9"
                                autocomplete="address-line1"
                                name="shipping_street"
                                placeholder="Calle Gran Vía"
                            />
                            <InputError :message="errors.shipping_street" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="shipping_street_number">Número</Label>
                            <Input
                                id="shipping_street_number"
                                type="text"
                                required
                                :tabindex="10"
                                name="shipping_street_number"
                                placeholder="12"
                            />
                            <InputError :message="errors.shipping_street_number" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="shipping_apartment">
                            Piso / Puerta
                            <span class="text-xs text-muted-foreground">(opcional)</span>
                        </Label>
                        <Input
                            id="shipping_apartment"
                            type="text"
                            :tabindex="11"
                            autocomplete="address-line2"
                            name="shipping_apartment"
                            placeholder="3º B"
                        />
                        <InputError :message="errors.shipping_apartment" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="grid gap-2">
                            <Label for="shipping_city">Ciudad</Label>
                            <Input
                                id="shipping_city"
                                type="text"
                                required
                                :tabindex="12"
                                autocomplete="address-level2"
                                name="shipping_city"
                                placeholder="Madrid"
                            />
                            <InputError :message="errors.shipping_city" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="shipping_postal_code">Código postal</Label>
                            <Input
                                id="shipping_postal_code"
                                type="text"
                                required
                                :tabindex="13"
                                autocomplete="postal-code"
                                name="shipping_postal_code"
                                placeholder="28001"
                            />
                            <InputError :message="errors.shipping_postal_code" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="shipping_state_province">
                            Provincia
                            <span class="text-xs text-muted-foreground">(opcional)</span>
                        </Label>
                        <Input
                            id="shipping_state_province"
                            type="text"
                            :tabindex="14"
                            autocomplete="address-level1"
                            name="shipping_state_province"
                            placeholder="Madrid"
                        />
                        <InputError :message="errors.shipping_state_province" />
                    </div>
                </div>

                <Button
                    type="submit"
                    class="w-full"
                    tabindex="15"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Crear cuenta
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                ¿Ya tienes cuenta?
                <TextLink :href="login()" class="underline underline-offset-4" :tabindex="16">
                    Inicia sesión
                </TextLink>
            </div>
        </Form>
    </AuthBase>
</template>
