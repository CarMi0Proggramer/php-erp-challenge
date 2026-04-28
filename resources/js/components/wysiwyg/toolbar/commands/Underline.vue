<script setup lang="ts">
import { Underline } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useEditorInstance } from '../../composables';

const editor = useEditorInstance();
</script>
<template>
    <Button
        type="button"
        size="icon"
        variant="ghost"
        @click="editor?.chain().focus().toggleUnderline().run()"
        :class="{ 'bg-muted': editor?.isActive('underline') }"
    >
        <Underline class="size-4.5" />
    </Button>
</template>
