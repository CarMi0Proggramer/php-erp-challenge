<script setup lang="ts">
import { EditorContent, useEditor } from '@tiptap/vue-3';
import { onBeforeUnmount, provide } from 'vue';
import { EditorKey } from './composables';
import { editorProps, extensions } from './config';
import { Toolbar } from './toolbar';

const content = defineModel<string>();
const editor = useEditor({
    extensions,
    editorProps,
    content: content.value,
    onUpdate: ({ editor }) => (content.value = editor.getHTML()),
});

onBeforeUnmount(() => {
    editor.value?.destroy();
});

provide(EditorKey, editor);
</script>
<template>
    <div class="flex flex-col gap-6">
        <Toolbar />
        <EditorContent :editor="editor" />
    </div>
</template>
