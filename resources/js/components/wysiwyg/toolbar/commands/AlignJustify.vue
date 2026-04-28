<script setup lang="ts">
import { AlignJustify } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useEditorInstance } from '../../composables';

const editor = useEditorInstance();
</script>
<template>
    <Button
        type="button"
        size="icon"
        variant="ghost"
        @click="editor?.chain().focus().setTextAlign('justify').run()"
        :class="{
            'bg-muted': editor?.isActive({ textAlign: 'justify' }),
        }"
    >
        <AlignJustify class="size-4.5" />
    </Button>
</template>
