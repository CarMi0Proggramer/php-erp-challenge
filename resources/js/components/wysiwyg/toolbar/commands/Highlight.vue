<script setup lang="ts">
import { Ban, Highlighter } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { useEditorInstance } from '../../composables';

const editor = useEditorInstance();
const COLORS = ['#22c55e', '#60a5fa', '#f87171', '#a78bfa', '#facc15'];

function setColor(color: string) {
    editor.value?.chain().focus().toggleHighlight({ color }).run();
}

function isActive(color: string) {
    return editor.value?.isActive('highlight', { color });
}

function clear() {
    editor.value?.chain().focus().unsetHighlight().run();
}
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button
                size="icon"
                variant="ghost"
                :class="{ 'bg-muted': editor?.isActive('highlight') }"
            >
                <Highlighter class="size-4" />
            </Button>
        </PopoverTrigger>
        <PopoverContent
            class="flex w-auto items-center gap-2 rounded-xl border bg-popover p-2 shadow-md"
        >
            <Button
                v-for="color in COLORS"
                size="icon"
                variant="ghost"
                class="size-6 rounded-full border transition-all"
                :key="color"
                :style="{
                    borderColor: color,
                    backgroundColor: `${color}99`,
                }"
                :class="[
                    isActive(color)
                        ? 'ring-2 ring-ring ring-offset-2 ring-offset-background'
                        : 'hover:scale-110',
                ]"
                @click="setColor(color)"
            />
            <Button
                size="icon"
                variant="ghost"
                class="flex size-6 items-center justify-center rounded-full border text-muted-foreground hover:bg-muted"
                @click="clear"
            >
                <Ban class="size-6" />
            </Button>
        </PopoverContent>
    </Popover>
</template>
