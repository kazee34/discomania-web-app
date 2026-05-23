<script setup lang="ts">
import { computed } from 'vue';
import { Input } from '@/components/ui/input';

defineProps<{
    genres: string[];
    countries: string[];
    decades: number[];
}>();

const search        = defineModel<string>('search',        { default: '' });
const selectedGenre   = defineModel<string>('selectedGenre',   { default: '' });
const selectedCountry = defineModel<string>('selectedCountry', { default: '' });
const selectedDecade  = defineModel<number | ''>('selectedDecade',  { default: '' });
const sortBy          = defineModel<string>('sortBy',          { default: 'default' });

const activeFilters = computed(
    () => [selectedGenre.value, selectedCountry.value, selectedDecade.value, search.value].filter(Boolean).length,
);

function clearAll() {
    search.value         = '';
    selectedGenre.value   = '';
    selectedCountry.value = '';
    selectedDecade.value  = '';
    sortBy.value          = 'default';
}
</script>

<template>
    <div class="flex flex-col gap-4">
        <!-- Row 1: search + sort -->
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
            <Input
                v-model="search"
                placeholder="Buscar por artista o álbum..."
                class="sm:max-w-xs"
            />

            <div class="flex items-center gap-2 sm:ml-auto">
                <select
                    v-model="sortBy"
                    class="h-9 w-48 rounded-md border bg-background px-3 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-ring"
                >
                    <option value="default">Relevancia</option>
                    <option value="artist_asc">Artista A → Z</option>
                    <option value="artist_desc">Artista Z → A</option>
                    <option value="price_asc">Precio: menor primero</option>
                    <option value="price_desc">Precio: mayor primero</option>
                    <option value="year_asc">Año: más antiguo</option>
                    <option value="year_desc">Año: más reciente</option>
                </select>

                <button
                    v-if="activeFilters > 0"
                    class="text-xs text-muted-foreground underline underline-offset-2 hover:text-foreground whitespace-nowrap"
                    @click="clearAll"
                >
                    Limpiar ({{ activeFilters }})
                </button>
            </div>
        </div>

        <!-- Row 2: genre pills -->
        <div class="flex flex-wrap gap-2">
            <button
                class="rounded-full border px-3 py-1 text-sm transition-colors"
                :class="selectedGenre === '' ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'"
                @click="selectedGenre = ''"
            >
                Todos
            </button>
            <button
                v-for="genre in genres"
                :key="genre"
                class="rounded-full border px-3 py-1 text-sm transition-colors"
                :class="selectedGenre === genre ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'"
                @click="selectedGenre = selectedGenre === genre ? '' : genre"
            >
                {{ genre }}
            </button>
        </div>

        <!-- Row 3: decade pills -->
        <div v-if="decades.length > 0" class="flex flex-wrap gap-2">
            <span class="self-center text-xs font-medium text-muted-foreground mr-1">Década:</span>
            <button
                class="rounded-full border px-3 py-1 text-xs transition-colors"
                :class="selectedDecade === '' ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'"
                @click="selectedDecade = ''"
            >
                Todas
            </button>
            <button
                v-for="decade in decades"
                :key="decade"
                class="rounded-full border px-3 py-1 text-xs transition-colors"
                :class="selectedDecade === decade ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'"
                @click="selectedDecade = selectedDecade === decade ? '' : decade"
            >
                {{ decade }}s
            </button>
        </div>

        <!-- Row 4: country pills -->
        <div v-if="countries.length > 0" class="flex flex-wrap gap-2">
            <span class="self-center text-xs font-medium text-muted-foreground mr-1">País:</span>
            <button
                class="rounded-full border px-3 py-1 text-xs transition-colors"
                :class="selectedCountry === '' ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'"
                @click="selectedCountry = ''"
            >
                Todos
            </button>
            <button
                v-for="country in countries"
                :key="country"
                class="rounded-full border px-3 py-1 text-xs transition-colors"
                :class="selectedCountry === country ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'"
                @click="selectedCountry = selectedCountry === country ? '' : country"
            >
                {{ country }}
            </button>
        </div>
    </div>
</template>
