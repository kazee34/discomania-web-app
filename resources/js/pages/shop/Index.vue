<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { useCart } from '@/composables/useCart';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
import ShopFilters from '@/components/shop/ShopFilters.vue';
import ProductCard from '@/components/shop/ProductCard.vue';

interface Product {
    id: number;
    slug: string;
    artist: string;
    albumTitle: string;
    price: number;
    genre: string;
    country: string;
    releaseYear: number;
    coverImageUrl: string;
}

const props = defineProps<{ products: Product[] }>();

const { fetchCart } = useCart();

const search = ref('');
const selectedGenre = ref('');

onMounted(() => fetchCart());

const genres = computed(() => {
    const all = props.products.map((p) => p.genre).filter(Boolean);
    return [...new Set(all)].sort();
});

const filtered = computed(() =>
    props.products.filter((p) => {
        const matchesSearch =
            !search.value ||
            p.artist.toLowerCase().includes(search.value.toLowerCase()) ||
            p.albumTitle.toLowerCase().includes(search.value.toLowerCase());
        const matchesGenre = !selectedGenre.value || p.genre === selectedGenre.value;
        return matchesSearch && matchesGenre;
    }),
);
</script>

<template>
    <Head title="Tienda — Discomania" />

    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
            <div class="mb-8">
                <h1 class="mb-1 text-3xl font-bold">Catálogo</h1>
                <p class="mb-6 text-muted-foreground">{{ filtered.length }} discos disponibles</p>

                <ShopFilters
                    v-model:search="search"
                    v-model:selected-genre="selectedGenre"
                    :genres="genres"
                />
            </div>

            <div
                v-if="filtered.length > 0"
                class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
            >
                <ProductCard
                    v-for="product in filtered"
                    :key="product.id"
                    :product="product"
                />
            </div>

            <div v-else class="py-24 text-center text-muted-foreground">
                <p class="text-lg font-medium">No se encontraron discos</p>
                <p class="text-sm mt-1">Prueba con otro artista o género</p>
            </div>
        </main>
    </div>
</template>