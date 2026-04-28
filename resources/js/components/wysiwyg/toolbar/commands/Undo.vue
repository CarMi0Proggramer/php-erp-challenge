<script setup lang="ts">
import { Undo2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useEditorInstance } from '../../composables';

const editor = useEditorInstance();
</script>

<template>
    <Button
        type="button"
        size="icon"
        variant="ghost"
        @click="editor?.chain().focus().undo().run()"
        :disabled="!editor?.can().undo()"
    >
        <Undo2 class="size-4" />
    </Button>
</template>
