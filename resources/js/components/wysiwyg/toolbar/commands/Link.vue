<script setup lang="ts">
import { CornerDownLeft, Link, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { useEditorInstance } from '../../composables';

const editor = useEditorInstance();
const url = ref('');

function setLink() {
    if (!editor.value) {
        return;
    }

    const { from, to } = editor.value.state.selection;
    const hasSelection = from !== to;

    if (hasSelection) {
        editor.value
            .chain()
            .focus()
            .extendMarkRange('link')
            .setLink({ href: url.value })
            .run();
    } else {
        editor.value
            .chain()
            .focus()
            .insertContent(
                `<a target="_blank" rel="noopener noreferrer nofollow" href="${url.value}">${url.value}</a>`,
            )
            .run();
    }

    url.value = '';
}

function clearLink() {
    editor.value?.chain().focus().unsetLink().run();
}
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button
                size="icon"
                variant="ghost"
                :class="{ 'bg-muted': editor?.isActive('link') }"
            >
                <Link class="size-4" />
            </Button>
        </PopoverTrigger>

        <PopoverContent class="flex w-96 gap-6 rounded-xl">
            <Input
                v-model="url"
                placeholder="https://..."
                @keydown.enter.prevent="setLink"
            />
            <div class="flex items-center gap-2">
                <Button
                    size="sm"
                    variant="outline"
                    :disabled="!url"
                    @click="setLink"
                >
                    <CornerDownLeft class="size-4" />
                </Button>

                <Button size="sm" variant="outline" @click="clearLink">
                    <Trash2 class="size-4" />
                </Button>
            </div>
        </PopoverContent>
    </Popover>
</template>
