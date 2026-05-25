<script setup lang="ts">
import { ref } from 'vue';
import { Input } from '@/components/ui/input';
import { Eye, EyeOff } from 'lucide-vue-next';

defineOptions({ inheritAttrs: false });

const show = ref(false);

function startShow() { show.value = true; }
function stopShow()  { show.value = false; }
</script>

<template>
    <div class="relative">
        <Input
            v-bind="$attrs"
            :type="show ? 'text' : 'password'"
            class="pr-10"
        />
        <button
            type="button"
            class="absolute inset-y-0 right-0 flex items-center px-3 text-muted-foreground select-none"
            @mousedown.prevent="startShow"
            @mouseup="stopShow"
            @mouseleave="stopShow"
            @touchstart.prevent="startShow"
            @touchend="stopShow"
            tabindex="-1"
            aria-label="Mantén pulsado para mostrar la contraseña"
        >
            <Eye v-if="!show" class="h-4 w-4" />
            <EyeOff v-else class="h-4 w-4" />
        </button>
    </div>
</template>
