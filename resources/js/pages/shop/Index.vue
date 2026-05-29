<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import ProductCard from '@/components/shop/ProductCard.vue';
import ShopFilters from '@/components/shop/ShopFilters.vue';
import ShopNavbar from '@/components/shop/ShopNavbar.vue';
import { Button } from '@/components/ui/button';
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
const priceMin        = ref<number | ''>('');
const priceMax        = ref<number | ''>('');
const currentPage     = ref(1);
const PER_PAGE        = 20;

function syncFromUrl(): void {
    const p = new URLSearchParams(window.location.search);
    const page = parseInt(p.get('page') ?? '1');
    if (!isNaN(page) && page > 1) currentPage.value = page;
    if (p.get('q'))       search.value          = p.get('q')!;
    if (p.get('genre'))   selectedGenre.value   = p.get('genre')!;
    if (p.get('country')) selectedCountry.value = p.get('country')!;
    if (p.get('decade'))  selectedDecade.value  = parseInt(p.get('decade')!);
    if (p.get('sort'))    sortBy.value           = p.get('sort')!;
    if (p.get('pmin'))    priceMin.value         = parseFloat(p.get('pmin')!);
    if (p.get('pmax'))    priceMax.value         = parseFloat(p.get('pmax')!);
}

function syncToUrl(): void {
    const url = new URL(window.location.href);
    const set = (k: string, v: string | number | '') => {
        if (v !== '' && v !== 'default' && v !== null) url.searchParams.set(k, String(v));
        else url.searchParams.delete(k);
    };
    set('page', currentPage.value > 1 ? currentPage.value : '');
    set('q',       search.value);
    set('genre',   selectedGenre.value);
    set('country', selectedCountry.value);
    set('decade',  selectedDecade.value);
    set('sort',    sortBy.value);
    set('pmin',    priceMin.value);
    set('pmax',    priceMax.value);
    history.replaceState({}, '', url.toString());
}

onMounted(() => {
    syncFromUrl();
    fetchCart();
});

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
        const matchesPrice   =
            (priceMin.value === '' || p.price >= priceMin.value) &&
            (priceMax.value === '' || p.price <= priceMax.value);

        return matchesSearch && matchesGenre && matchesCountry && matchesDecade && matchesPrice;
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

const maxPrice = computed(() => Math.ceil(Math.max(...props.products.map(p => p.price))));

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / PER_PAGE)));

const paginated = computed(() => {
    const start = (currentPage.value - 1) * PER_PAGE;
    return filtered.value.slice(start, start + PER_PAGE);
});

function onFilterChange() {
    currentPage.value = 1;
    syncToUrl();
}

function goToPage(page: number) {
    currentPage.value = Math.max(1, Math.min(page, totalPages.value));
    syncToUrl();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<template>
    <Head title="Tienda" />

    <div class="min-h-screen bg-background">
        <ShopNavbar />

        <main class="mx-auto max-w-screen-2xl px-4 py-10 sm:px-6">
            <div class="flex gap-10">
                <!-- Filtros -->
                <aside class="hidden w-72 shrink-0 lg:block">
                    <ShopFilters
                        v-model:search="search"
                        v-model:selected-genre="selectedGenre"
                        v-model:selected-country="selectedCountry"
                        v-model:selected-decade="selectedDecade"
                        v-model:sort-by="sortBy"
                        v-model:price-min="priceMin"
                        v-model:price-max="priceMax"
                        :genres="genres"
                        :countries="countries"
                        :decades="decades"
                        :max-price="maxPrice"
                        @update:search="onFilterChange"
                        @update:selected-genre="onFilterChange"
                        @update:selected-country="onFilterChange"
                        @update:selected-decade="onFilterChange"
                        @update:sort-by="onFilterChange"
                        @update:price-min="onFilterChange"
                        @update:price-max="onFilterChange"
                    />
                </aside>

                <!-- Productos -->
                <div class="flex-1 min-w-0">
                    <div class="mb-6">
                        <h1 class="mb-1 text-3xl font-bold">Catálogo</h1>
                        <p class="text-muted-foreground">
                            {{ filtered.length }} disco{{ filtered.length !== 1 ? 's' : '' }} disponible{{ filtered.length !== 1 ? 's' : '' }}
                            <span v-if="totalPages > 1" class="ml-1">— Página {{ currentPage }} de {{ totalPages }}</span>
                        </p>
                    </div>

                    <div
                        v-if="paginated.length > 0"
                        class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4"
                    >
                        <ProductCard
                            v-for="product in paginated"
                            :key="product.id"
                            :product="product"
                        />
                    </div>

                    <div v-else class="py-24 text-center text-muted-foreground">
                        <p class="text-lg font-medium">No se encontraron discos</p>
                        <p class="mt-1 text-sm">Prueba con otro artista, género o año</p>
                    </div>

                    <!-- Paginación -->
                    <div v-if="totalPages > 1" class="mt-10 flex items-center justify-center gap-2">
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="currentPage === 1"
                            @click="goToPage(1)"
                        >
                            «
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="currentPage === 1"
                            @click="goToPage(currentPage - 1)"
                        >
                            Anterior
                        </Button>

                        <template v-for="page in totalPages" :key="page">
                            <Button
                                v-if="page === 1 || page === totalPages || Math.abs(page - currentPage) <= 2"
                                :variant="page === currentPage ? 'default' : 'outline'"
                                size="sm"
                                class="min-w-9"
                                :class="page === currentPage ? 'bg-violet-600 hover:bg-violet-700 text-white border-violet-600' : ''"
                                @click="goToPage(page)"
                            >
                                {{ page }}
                            </Button>
                            <span
                                v-else-if="page === currentPage - 3 || page === currentPage + 3"
                                class="px-1 text-muted-foreground"
                            >…</span>
                        </template>

                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="currentPage === totalPages"
                            @click="goToPage(currentPage + 1)"
                        >
                            Siguiente
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            :disabled="currentPage === totalPages"
                            @click="goToPage(totalPages)"
                        >
                            »
                        </Button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
