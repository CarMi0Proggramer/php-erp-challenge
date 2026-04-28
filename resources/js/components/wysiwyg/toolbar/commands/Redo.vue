<script setup lang="ts">
import { Redo2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useEditorInstance } from '../../composables';

const editor = useEditorInstance();
</script>

<template>
    <Button
        type="button"
        size="icon"
        variant="ghost"
        @click="editor?.chain().focus().redo().run()"
        :disabled="!editor?.can().redo()"
    >
        <Redo2 class="size-4" />
    </Button>
</template>
