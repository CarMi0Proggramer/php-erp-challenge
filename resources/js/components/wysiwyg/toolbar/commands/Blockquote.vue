<script setup lang="ts">
import { TextQuote } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useEditorInstance } from '../../composables';

const editor = useEditorInstance();
</script>
<template>
    <Button
        type="button"
        size="icon"
        variant="ghost"
        @click="editor?.chain().focus().toggleBlockquote().run()"
        :class="{ 'bg-muted': editor?.isActive('blockquote') }"
    >
        <TextQuote class="size-4.5" />
    </Button>
</template>
