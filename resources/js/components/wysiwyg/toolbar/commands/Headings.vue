<script setup lang="ts">
import {
    Heading,
    Heading1,
    Heading2,
    Heading3,
    Heading4,
    ChevronDown,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useEditorInstance } from '../../composables';

const editor = useEditorInstance();
const HEADINGS = [
    { icon: Heading1, level: 1 },
    { icon: Heading2, level: 2 },
    { icon: Heading3, level: 3 },
    { icon: Heading4, level: 4 },
];

const currentHeading = computed(() => {
    for (const heading of HEADINGS) {
        if (editor.value?.isActive('heading', { level: heading.level })) {
            return heading;
        }
    }

    return null;
});

function toggleHeading(level: number) {
    editor.value
        ?.chain()
        .focus()
        .toggleHeading({ level: level as any })
        .run();
}
</script>
<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button
                :class="{ 'bg-muted': !!currentHeading }"
                variant="ghost"
                size="sm"
            >
                <component
                    :is="currentHeading ? currentHeading.icon : Heading"
                />
                <ChevronDown class="size-3" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent @close-auto-focus="$event.preventDefault()">
            <DropdownMenuItem @click="toggleHeading(1)">
                <Heading1 />
            </DropdownMenuItem>
            <DropdownMenuItem @click="toggleHeading(2)">
                <Heading2 />
            </DropdownMenuItem>
            <DropdownMenuItem @click="toggleHeading(3)">
                <Heading3 />
            </DropdownMenuItem>
            <DropdownMenuItem @click="toggleHeading(4)">
                <Heading4 />
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
