import Highlight from '@tiptap/extension-highlight';
import Image from '@tiptap/extension-image';
import TextAlign from '@tiptap/extension-text-align';
import { Placeholder } from '@tiptap/extensions';
import StarterKit from '@tiptap/starter-kit';
import type { Extensions } from '@tiptap/vue-3';

export const extensions: Extensions = [
    StarterKit,
    Image.extend({
        addAttributes() {
            return {
                ...this.parent?.(),
                id: {
                    default: null,
                    parseHTML: (element) => element.getAttribute('data-id'),
                    renderHTML: (attributes) => ({
                        ...(attributes.id ? { 'data-id': attributes.id } : {}),
                    }),
                },
            };
        },
    }),
    Placeholder.configure({
        placeholder: 'Toque para começar a escrever',
        emptyNodeClass:
            'first:before:pointer-events-none first:before:float-left first:before:h-0 first:before:text-muted first:before:content-[attr(data-placeholder)] text-base!',
    }),
    TextAlign.configure({ types: ['heading', 'paragraph'] }),
    Highlight.configure({ multicolor: true }),
];
