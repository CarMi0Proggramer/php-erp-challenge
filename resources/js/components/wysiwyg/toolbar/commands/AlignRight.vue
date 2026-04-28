<script setup lang="ts">
import { AlignRight } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useEditorInstance } from '../../composables';

const editor = useEditorInstance();
</script>
<template>
    <Button
        type="button"
        size="icon"
        variant="ghost"
        @click="editor?.chain().focus().setTextAlign('right').run()"
        :class="{ 'bg-muted': editor?.isActive({ textAlign: 'right' }) }"
    >
        <AlignRight class="size-4.5" />
    </Button>
</template>
