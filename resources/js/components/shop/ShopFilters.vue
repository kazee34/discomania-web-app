<script setup lang="ts">
import { Input } from '@/components/ui/input';

defineProps<{
    genres: string[];
}>();

const search = defineModel<string>('search', { default: '' });
const selectedGenre = defineModel<string>('selectedGenre', { default: '' });
</script>

<template>
    <div class="flex flex-col gap-3 sm:flex-row">
        <Input
            v-model="search"
            placeholder="Buscar por artista o álbum..."
            class="sm:max-w-xs"
        />
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
                @click="selectedGenre = genre"
            >
                {{ genre }}
            </button>
        </div>
    </div>
</template>