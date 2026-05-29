<script setup lang="ts">
import discomaniaLogo from '@/assets/discomania_logo.jpg';
import { Head } from '@inertiajs/vue3';
import { Truck, Star, Headphones, ShieldCheck } from 'lucide-vue-next';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
</script>

<template>
    <Head title="Acerca de nosotros" />

    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <main class="mx-auto max-w-4xl px-4 py-16 sm:px-6">

            <!-- Intro -->
            <h1 class="mb-4 text-4xl font-bold">Acerca de nosotros</h1>
            <p class="mb-6 text-lg text-muted-foreground leading-relaxed">
                Discomania es tu tienda de referencia para vinilos y discos de colección. Nos apasiona la música y llevamos años ayudando a coleccionistas y amantes del sonido analógico a encontrar ese álbum especial que andaban buscando.
            </p>
            <p class="mb-6 text-muted-foreground leading-relaxed">
                Nuestra selección abarca desde clásicos del rock y el jazz hasta rarezas de culto, ediciones limitadas y primeras presiones de todo el mundo. Cada disco de nuestro catálogo ha sido revisado y catalogado con cariño.
            </p>
            <p class="mb-16 text-muted-foreground leading-relaxed">
                Si tienes alguna pregunta, sugerencia o simplemente quieres hablar de música, no dudes en ponerte en contacto con nosotros. Estamos aquí para ayudarte.
            </p>

            <h2 class="mb-8 text-2xl font-bold">¿Por qué elegirnos?</h2>
            <div class="mb-16 grid gap-6 sm:grid-cols-2">
                <div class="flex gap-4 rounded-xl border bg-card p-6">
                    <div class="shrink-0 rounded-lg bg-primary/10 p-3">
                        <Truck class="h-5 w-5 text-primary" />
                    </div>
                    <div>
                        <h3 class="font-semibold mb-1">Envío gratis desde 60 €</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">Todos los pedidos superiores a 60 € disfrutan de envío gratuito a toda España.</p>
                    </div>
                </div>
                <div class="flex gap-4 rounded-xl border bg-card p-6">
                    <div class="shrink-0 rounded-lg bg-primary/10 p-3">
                        <Star class="h-5 w-5 text-primary" />
                    </div>
                    <div>
                        <h3 class="font-semibold mb-1">Discos revisados y certificados</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">Cada vinilo pasa por un control de calidad antes de entrar en nuestro catálogo.</p>
                    </div>
                </div>
                <div class="flex gap-4 rounded-xl border bg-card p-6">
                    <div class="shrink-0 rounded-lg bg-primary/10 p-3">
                        <Headphones class="h-5 w-5 text-primary" />
                    </div>
                    <div>
                        <h3 class="font-semibold mb-1">Atención personalizada</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">¿No encuentras lo que buscas? Cuéntanoslo y te ayudamos a localizarlo.</p>
                    </div>
                </div>
                <div class="flex gap-4 rounded-xl border bg-card p-6">
                    <div class="shrink-0 rounded-lg bg-primary/10 p-3">
                        <ShieldCheck class="h-5 w-5 text-primary" />
                    </div>
                    <div>
                        <h3 class="font-semibold mb-1">Pago seguro</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">Tarjeta de crédito o débito SEPA. Tus datos siempre protegidos.</p>
                    </div>
                </div>
            </div>

            <img :src="discomaniaLogo" alt="Discomania" class="w-full rounded-xl" />
        </main>
    </div>
</template>
