<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
</script>

<template>
    <Head title="Acerca de nosotros" />

    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <main class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
            <h1 class="mb-4 text-4xl font-bold">Acerca de nosotros</h1>
            <p class="mb-6 text-lg text-muted-foreground leading-relaxed">
                Discomania es tu tienda de referencia para vinilos y discos de colección. Nos apasiona la música y llevamos años ayudando a coleccionistas y amantes del sonido analógico a encontrar ese álbum especial que andaban buscando.
            </p>
            <p class="mb-6 text-muted-foreground leading-relaxed">
                Nuestra selección abarca desde clásicos del rock y el jazz hasta rarezas de culto, ediciones limitadas y primeras presiones de todo el mundo. Cada disco de nuestro catálogo ha sido revisado y catalogado con cariño.
            </p>
            <p class="text-muted-foreground leading-relaxed">
                Si tienes alguna pregunta, sugerencia o simplemente quieres hablar de música, no dudes en ponerte en contacto con nosotros. Estamos aquí para ayudarte.
            </p>
        </main>
    </div>
</template>
