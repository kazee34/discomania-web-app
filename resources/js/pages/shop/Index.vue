<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import ProductCard from '@/components/shop/ProductCard.vue';
import ShopFilters from '@/components/shop/ShopFilters.vue';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
import { useCart } from '@/composables/useCart';

interface Product {
    id: number;
    slug: string;
    artist: string;
    albumTitle: string;
    price: number;
    genre: string;
    country: string;
    releaseYear: number;
    coverImageUrl: string | null;
    stockQuantity: number;
}

const props = defineProps<{ products: Product[] }>();

const { fetchCart } = useCart();

const search          = ref('');
const selectedGenre   = ref('');
const selectedCountry = ref('');
const selectedDecade  = ref<number | ''>('');
const sortBy          = ref('default');

onMounted(() => fetchCart());

const genres = computed(() => {
    const all = props.products.map((p) => p.genre).filter(Boolean);
    return [...new Set(all)].sort();
});

const countries = computed(() => {
    const all = props.products.map((p) => p.country).filter(Boolean);
    return [...new Set(all)].sort();
});

const decades = computed(() => {
    const all = props.products
        .map((p) => p.releaseYear)
        .filter(Boolean)
        .map((y) => Math.floor(y / 10) * 10);
    return [...new Set(all)].sort();
});

const filtered = computed(() => {
    const q = search.value.toLowerCase();

    const result = props.products.filter((p) => {
        const matchesSearch =
            !q ||
            p.artist.toLowerCase().includes(q) ||
            p.albumTitle.toLowerCase().includes(q);
        const matchesGenre   = !selectedGenre.value   || p.genre === selectedGenre.value;
        const matchesCountry = !selectedCountry.value || p.country === selectedCountry.value;
        const matchesDecade  =
            selectedDecade.value === '' ||
            Math.floor(p.releaseYear / 10) * 10 === selectedDecade.value;

        return matchesSearch && matchesGenre && matchesCountry && matchesDecade;
    });

    switch (sortBy.value) {
        case 'artist_asc':   return [...result].sort((a, b) => a.artist.localeCompare(b.artist));
        case 'artist_desc':  return [...result].sort((a, b) => b.artist.localeCompare(a.artist));
        case 'price_asc':    return [...result].sort((a, b) => a.price - b.price);
        case 'price_desc':   return [...result].sort((a, b) => b.price - a.price);
        case 'year_asc':     return [...result].sort((a, b) => a.releaseYear - b.releaseYear);
        case 'year_desc':    return [...result].sort((a, b) => b.releaseYear - a.releaseYear);
        default:             return result;
    }
});
</script>

<template>
    <Head title="Tienda — Discomania" />

    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
            <div class="mb-8">
                <h1 class="mb-1 text-3xl font-bold">Catálogo</h1>
                <p class="mb-6 text-muted-foreground">{{ filtered.length }} disco{{ filtered.length !== 1 ? 's' : '' }} disponible{{ filtered.length !== 1 ? 's' : '' }}</p>

                <ShopFilters
                    v-model:search="search"
                    v-model:selected-genre="selectedGenre"
                    v-model:selected-country="selectedCountry"
                    v-model:selected-decade="selectedDecade"
                    v-model:sort-by="sortBy"
                    :genres="genres"
                    :countries="countries"
                    :decades="decades"
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
                <p class="mt-1 text-sm">Prueba con otro artista, género o año</p>
            </div>
        </main>
    </div>
</template>
