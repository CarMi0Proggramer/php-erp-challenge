<script setup lang="ts">
import { AlignLeft } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useEditorInstance } from '../../composables';

const editor = useEditorInstance();
</script>
<template>
    <Button
        type="button"
        size="icon"
        variant="ghost"
        @click="editor?.chain().focus().setTextAlign('left').run()"
        :class="{ 'bg-muted': editor?.isActive({ textAlign: 'left' }) }"
    >
        <AlignLeft class="size-4.5" />
    </Button>
</template>
