<script setup lang="ts">
import { ChevronDown, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Slider from '@/components/ui/slider/Slider.vue';
import { Input } from '@/components/ui/input';

const props = defineProps<{
    genres: string[];
    countries: string[];
    decades: number[];
    maxPrice: number;
}>();

const search          = defineModel<string>('search',          { default: '' });
const selectedGenre   = defineModel<string>('selectedGenre',   { default: '' });
const selectedCountry = defineModel<string>('selectedCountry', { default: '' });
const selectedDecade  = defineModel<number | ''>('selectedDecade', { default: '' });
const sortBy          = defineModel<string>('sortBy',          { default: 'default' });
const priceMin        = defineModel<number | ''>('priceMin',   { default: '' });
const priceMax        = defineModel<number | ''>('priceMax',   { default: '' });

const PRICE_MIN = 0;

const sliderValue = computed({
    get: () => [
        priceMin.value === '' ? PRICE_MIN : Number(priceMin.value),
        priceMax.value === '' ? props.maxPrice : Number(priceMax.value),
    ],
    set: ([min, max]: number[]) => {
        priceMin.value = min === PRICE_MIN ? '' : min;
        priceMax.value = max === props.maxPrice ? '' : max;
    },
});

const openGenre   = ref(false);
const openDecade  = ref(false);
const openCountry = ref(false);
const openSort    = ref(false);
const openPrice   = ref(false);

const activeFilters = () =>
    [selectedGenre.value, selectedCountry.value, selectedDecade.value, search.value,
     priceMin.value !== '' ? priceMin.value : null,
     priceMax.value !== '' ? priceMax.value : null].filter(Boolean).length;

function clearAll() {
    search.value          = '';
    selectedGenre.value   = '';
    selectedCountry.value = '';
    selectedDecade.value  = '';
    sortBy.value          = 'default';
    priceMin.value        = '';
    priceMax.value        = '';
}
</script>

<template>
    <div class="flex flex-col gap-1">
        <!-- Search -->
        <div class="mb-4">
            <Input v-model="search" placeholder="Buscar artista o álbum..." class="w-full" />
        </div>

        <!-- Precio -->
        <div class="border-t pt-3">
            <button
                class="flex w-full items-center justify-between py-1.5 text-sm font-semibold"
                @click="openPrice = !openPrice"
            >
                Precio (€)
                <ChevronDown
                    class="h-4 w-4 text-muted-foreground transition-transform duration-200"
                    :class="{ 'rotate-180': openPrice }"
                />
            </button>
            <div v-show="openPrice" class="mt-4 px-1">
                <Slider
                    v-model="sliderValue"
                    :min="PRICE_MIN"
                    :max="props.maxPrice"
                    :step="1"
                    class="mb-3"
                />
                <div class="flex items-center justify-between text-xs text-muted-foreground">
                    <span>{{ sliderValue[0] }} €</span>
                    <span>{{ sliderValue[1] }} €</span>
                </div>
            </div>
        </div>

        <!-- Clear filters -->
        <button
            v-if="activeFilters() > 0"
            class="mb-3 flex items-center gap-2 rounded-lg bg-violet-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-violet-700 transition-colors"
            @click="clearAll"
        >
            <X class="h-4 w-4 shrink-0" />
            Limpiar filtros ({{ activeFilters() }})
        </button>

        <!-- Género -->
        <div class="border-t pt-3">
            <button
                class="flex w-full items-center justify-between py-1.5 text-sm font-semibold"
                @click="openGenre = !openGenre"
            >
                Género
                <ChevronDown
                    class="h-4 w-4 text-muted-foreground transition-transform duration-200"
                    :class="{ 'rotate-180': openGenre }"
                />
            </button>
            <div v-show="openGenre" class="mt-2 flex flex-col gap-0.5">
                <button
                    class="rounded px-2 py-1.5 text-left text-sm transition-colors hover:bg-muted"
                    :class="selectedGenre === '' ? 'font-medium text-foreground' : 'text-muted-foreground'"
                    @click="selectedGenre = ''"
                >
                    Todos
                </button>
                <button
                    v-for="genre in genres"
                    :key="genre"
                    class="rounded px-2 py-1.5 text-left text-sm transition-colors hover:bg-muted"
                    :class="selectedGenre === genre ? 'font-medium bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300' : 'text-muted-foreground'"
                    @click="selectedGenre = selectedGenre === genre ? '' : genre"
                >
                    {{ genre }}
                </button>
            </div>
        </div>

        <!-- Década -->
        <div v-if="decades.length > 0" class="border-t pt-3">
            <button
                class="flex w-full items-center justify-between py-1.5 text-sm font-semibold"
                @click="openDecade = !openDecade"
            >
                Década
                <ChevronDown
                    class="h-4 w-4 text-muted-foreground transition-transform duration-200"
                    :class="{ 'rotate-180': openDecade }"
                />
            </button>
            <div v-show="openDecade" class="mt-2 flex flex-col gap-0.5">
                <button
                    class="rounded px-2 py-1.5 text-left text-sm transition-colors hover:bg-muted"
                    :class="selectedDecade === '' ? 'font-medium text-foreground' : 'text-muted-foreground'"
                    @click="selectedDecade = ''"
                >
                    Todas
                </button>
                <button
                    v-for="decade in decades"
                    :key="decade"
                    class="rounded px-2 py-1.5 text-left text-sm transition-colors hover:bg-muted"
                    :class="selectedDecade === decade ? 'font-medium bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300' : 'text-muted-foreground'"
                    @click="selectedDecade = selectedDecade === decade ? '' : decade"
                >
                    {{ decade }}s
                </button>
            </div>
        </div>

        <!-- País -->
        <div v-if="countries.length > 0" class="border-t pt-3">
            <button
                class="flex w-full items-center justify-between py-1.5 text-sm font-semibold"
                @click="openCountry = !openCountry"
            >
                País
                <ChevronDown
                    class="h-4 w-4 text-muted-foreground transition-transform duration-200"
                    :class="{ 'rotate-180': openCountry }"
                />
            </button>
            <div v-show="openCountry" class="mt-2 flex flex-col gap-0.5">
                <button
                    class="rounded px-2 py-1.5 text-left text-sm transition-colors hover:bg-muted"
                    :class="selectedCountry === '' ? 'font-medium text-foreground' : 'text-muted-foreground'"
                    @click="selectedCountry = ''"
                >
                    Todos
                </button>
                <button
                    v-for="country in countries"
                    :key="country"
                    class="rounded px-2 py-1.5 text-left text-sm transition-colors hover:bg-muted"
                    :class="selectedCountry === country ? 'font-medium bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300' : 'text-muted-foreground'"
                    @click="selectedCountry = selectedCountry === country ? '' : country"
                >
                    {{ country }}
                </button>
            </div>
        </div>

        <!-- Ordenar -->
        <div class="border-t pt-3">
            <button
                class="flex w-full items-center justify-between py-1.5 text-sm font-semibold"
                @click="openSort = !openSort"
            >
                Ordenar por
                <ChevronDown
                    class="h-4 w-4 text-muted-foreground transition-transform duration-200"
                    :class="{ 'rotate-180': openSort }"
                />
            </button>
            <div v-show="openSort" class="mt-2 flex flex-col gap-0.5">
                <button
                    v-for="opt in [
                        { value: 'default',      label: 'Relevancia' },
                        { value: 'artist_asc',   label: 'Artista A → Z' },
                        { value: 'artist_desc',  label: 'Artista Z → A' },
                        { value: 'price_asc',    label: 'Precio: menor primero' },
                        { value: 'price_desc',   label: 'Precio: mayor primero' },
                        { value: 'year_asc',     label: 'Año: más antiguo' },
                        { value: 'year_desc',    label: 'Año: más reciente' },
                    ]"
                    :key="opt.value"
                    class="rounded px-2 py-1.5 text-left text-sm transition-colors hover:bg-muted"
                    :class="sortBy === opt.value ? 'font-medium bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-300' : 'text-muted-foreground'"
                    @click="sortBy = opt.value"
                >
                    {{ opt.label }}
                </button>
            </div>
        </div>
    </div>
</template>
