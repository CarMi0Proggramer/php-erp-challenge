import type { Editor } from '@tiptap/vue-3';
import { inject } from 'vue';
import type { ShallowRef, InjectionKey } from 'vue';

export const EditorKey: InjectionKey<ShallowRef<Editor | undefined>> =
    Symbol('editor');

export function useEditorInstance() {
    const editor = inject(EditorKey);

    if (!editor) {
        throw new Error('Editor not provided');
    }

    return editor;
}
