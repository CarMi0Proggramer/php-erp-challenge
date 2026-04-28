import type { EditorProps } from '@tiptap/pm/view';

export const editorProps: EditorProps<any> = {
    attributes: {
        class: 'prose dark:prose-invert min-h-96 min-w-full border border-input rounded-md bg-transparent dark:bg-input/30 p-4 shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
    },
};
