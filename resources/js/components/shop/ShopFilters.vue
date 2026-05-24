<script setup lang="ts">
import { ChevronDown } from 'lucide-vue-next';
import { ref } from 'vue';
import { Input } from '@/components/ui/input';

defineProps<{
    genres: string[];
    countries: string[];
    decades: number[];
}>();

const search          = defineModel<string>('search',          { default: '' });
const selectedGenre   = defineModel<string>('selectedGenre',   { default: '' });
const selectedCountry = defineModel<string>('selectedCountry', { default: '' });
const selectedDecade  = defineModel<number | ''>('selectedDecade', { default: '' });
const sortBy          = defineModel<string>('sortBy',          { default: 'default' });

const openGenre   = ref(true);
const openDecade  = ref(true);
const openCountry = ref(true);
const openSort    = ref(true);

const activeFilters = () =>
    [selectedGenre.value, selectedCountry.value, selectedDecade.value, search.value].filter(Boolean).length;

function clearAll() {
    search.value          = '';
    selectedGenre.value   = '';
    selectedCountry.value = '';
    selectedDecade.value  = '';
    sortBy.value          = 'default';
}
</script>

<template>
    <div class="flex flex-col gap-1">
        <!-- Search -->
        <div class="mb-3">
            <Input v-model="search" placeholder="Buscar artista o álbum..." class="w-full" />
        </div>

        <!-- Clear filters -->
        <button
            v-if="activeFilters() > 0"
            class="mb-2 text-left text-xs text-muted-foreground underline underline-offset-2 hover:text-foreground"
            @click="clearAll"
        >
            Limpiar filtros ({{ activeFilters() }})
        </button>

        <!-- Género -->
        <div class="border-t pt-3">
            <button
                class="flex w-full items-center justify-between py-1 text-sm font-semibold"
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
                    :class="selectedGenre === genre ? 'font-medium text-foreground' : 'text-muted-foreground'"
                    @click="selectedGenre = selectedGenre === genre ? '' : genre"
                >
                    {{ genre }}
                </button>
            </div>
        </div>

        <!-- Década -->
        <div v-if="decades.length > 0" class="border-t pt-3">
            <button
                class="flex w-full items-center justify-between py-1 text-sm font-semibold"
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
                    :class="selectedDecade === decade ? 'font-medium text-foreground' : 'text-muted-foreground'"
                    @click="selectedDecade = selectedDecade === decade ? '' : decade"
                >
                    {{ decade }}s
                </button>
            </div>
        </div>

        <!-- País -->
        <div v-if="countries.length > 0" class="border-t pt-3">
            <button
                class="flex w-full items-center justify-between py-1 text-sm font-semibold"
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
                    :class="selectedCountry === country ? 'font-medium text-foreground' : 'text-muted-foreground'"
                    @click="selectedCountry = selectedCountry === country ? '' : country"
                >
                    {{ country }}
                </button>
            </div>
        </div>

        <!-- Ordenar -->
        <div class="border-t pt-3">
            <button
                class="flex w-full items-center justify-between py-1 text-sm font-semibold"
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
                    :class="sortBy === opt.value ? 'font-medium text-foreground' : 'text-muted-foreground'"
                    @click="sortBy = opt.value"
                >
                    {{ opt.label }}
                </button>
            </div>
        </div>
    </div>
</template>
