import { inject } from 'vue';
import type { InjectionKey } from 'vue';

export type ImageUploader = {
    upload(file: File): Promise<{ id: string; url: string }>;
    delete(id: string): Promise<void>;
};

export const ImageUploaderKey: InjectionKey<ImageUploader> =
    Symbol('imageUploader');

export function useImageUploader() {
    const uploader = inject(ImageUploaderKey);

    return uploader;
}
