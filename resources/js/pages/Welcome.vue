<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
import { Button } from '@/components/ui/button';
import { register } from '@/routes';

withDefaults(
    defineProps<{ canRegister: boolean; vinylImages: string[] }>(),
    { canRegister: true, vinylImages: () => [] },
);

const placeholderCount = 10;
</script>

<template>
    <Head title="Discomania — Tu tienda de vinilos" />

    <div class="min-h-screen bg-background text-foreground flex flex-col">
        <ShopNavbar />

        <main class="flex-1">
            <!-- Hero -->
            <section class="relative overflow-hidden bg-black text-white">
                <!-- Decorative blobs -->
                <div class="pointer-events-none absolute -top-32 -left-32 h-96 w-96 rounded-full bg-violet-700/30 blur-3xl" />
                <div class="pointer-events-none absolute -bottom-24 right-0 h-80 w-80 rounded-full bg-violet-500/20 blur-3xl" />
                <div class="pointer-events-none absolute top-1/2 left-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-violet-800/20 blur-2xl" />

                <div class="relative mx-auto max-w-7xl px-4 py-32 sm:px-6 text-center flex flex-col items-center gap-6">
                    <span class="inline-block rounded-full border border-violet-500/40 bg-violet-500/10 px-4 py-1 text-sm text-violet-300">
                        Tu tienda de vinilos online
                    </span>
                    <h1 class="text-5xl font-bold tracking-tight sm:text-6xl lg:text-7xl leading-tight">
                        Música que<br class="hidden sm:block" /> puedes tocar
                    </h1>
                    <p class="max-w-xl text-lg text-white/60">
                        Descubre una selección de discos de todos los géneros y épocas.
                        Desde clásicos hasta rarezas, todo en un solo lugar.
                    </p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <Button as-child size="lg" class="bg-violet-600 hover:bg-violet-700 text-white">
                            <Link href="/shop">Ver catálogo</Link>
                        </Button>
                        <Button v-if="!$page.props.auth.user && canRegister" as-child size="lg" variant="outline" class="border-white/20 text-white hover:bg-white/10">
                            <Link :href="register()">Crear cuenta gratis</Link>
                        </Button>
                    </div>
                </div>
            </section>

            <!-- Carrusel de vinilos -->
            <section class="overflow-hidden border-y bg-black py-10">
                <div class="flex gap-4 animate-marquee w-max">
                    <template v-if="vinylImages.length > 0">
                        <template v-for="pass in 2" :key="pass">
                            <div
                                v-for="(src, i) in vinylImages"
                                :key="`${pass}-${i}`"
                                class="h-72 w-72 shrink-0 overflow-hidden rounded-xl border bg-muted"
                            >
                                <img :src="src" :alt="`Vinilo ${i + 1}`" class="h-full w-full object-cover" loading="lazy" decoding="async" />
                            </div>
                        </template>
                    </template>
                    <template v-else>
                        <!-- Placeholders hasta que añadas tus imágenes -->
                        <template v-for="pass in 2" :key="pass">
                            <div
                                v-for="i in placeholderCount"
                                :key="`${pass}-${i}`"
                                class="h-72 w-72 shrink-0 rounded-xl border bg-muted"
                            />
                        </template>
                    </template>
                </div>
            </section>

            <!-- Features -->
            <section class="mx-auto max-w-5xl px-4 py-24 sm:px-6">
                <div class="grid gap-8 sm:grid-cols-3 text-center">
                    <div class="flex flex-col items-center gap-3 rounded-2xl border bg-card p-8">
                        <div class="rounded-full bg-violet-100 p-4 dark:bg-violet-900/30">
                            <svg class="h-6 w-6 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold">Vinilos originales</h3>
                        <p class="text-sm text-muted-foreground">Todos nuestros discos son originales y verificados antes de ponerse a la venta.</p>
                    </div>
                    <div class="flex flex-col items-center gap-3 rounded-2xl border bg-card p-8">
                        <div class="rounded-full bg-violet-100 p-4 dark:bg-violet-900/30">
                            <svg class="h-6 w-6 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                        </div>
                        <h3 class="font-semibold">Envío gratis</h3>
                        <p class="text-sm text-muted-foreground">Envío gratuito en todos los pedidos, sin importar el importe.</p>
                    </div>
                    <div class="flex flex-col items-center gap-3 rounded-2xl border bg-card p-8">
                        <div class="rounded-full bg-violet-100 p-4 dark:bg-violet-900/30">
                            <svg class="h-6 w-6 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </div>
                        <h3 class="font-semibold">Devoluciones fáciles</h3>
                        <p class="text-sm text-muted-foreground">30 días para devolver tu compra sin complicaciones.</p>
                    </div>
                </div>
            </section>

            <!-- CTA final -->
            <section class="relative overflow-hidden bg-black py-28 text-center text-white">
                <div class="pointer-events-none absolute -top-20 left-1/4 h-72 w-72 rounded-full bg-violet-700/25 blur-3xl" />
                <div class="pointer-events-none absolute -bottom-20 right-1/4 h-72 w-72 rounded-full bg-violet-500/20 blur-3xl" />
                <div class="relative mx-auto max-w-xl px-4 flex flex-col items-center gap-4">
                    <h2 class="text-3xl font-bold">¿A qué esperas?</h2>
                    <p class="text-white/60">Cientos de títulos esperándote. Clásicos, rarezas y nuevas llegadas.</p>
                    <Button as-child size="lg" class="bg-violet-600 hover:bg-violet-700 text-white">
                        <Link href="/shop">Explorar catálogo</Link>
                    </Button>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t py-6 text-center text-xs text-muted-foreground">
            © {{ new Date().getFullYear() }} Discomania. Todos los derechos reservados.
        </footer>
    </div>
</template>

<style scoped>
@keyframes marquee {
    from { transform: translateX(0); }
    to   { transform: translateX(-50%); }
}

.animate-marquee {
    animation: marquee 30s linear infinite;
}

.animate-marquee:hover {
    animation-play-state: paused;
}
</style>