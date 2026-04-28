<script setup lang="ts">
import { watchOnce } from '@vueuse/core';
import { ImagePlus, Upload } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Spinner } from '@/components/ui/spinner';
import { useEditorInstance, useImageUploader } from '../../composables';

const editor = useEditorInstance();
const error = ref<string>();
const loading = ref<boolean>(false);
const imageUploader = useImageUploader();

async function handleImageUpload(input: HTMLInputElement) {
    if (!imageUploader || !input.files || input.files.length === 0) {
        return;
    }

    loading.value = true;
    error.value = undefined;

    try {
        const { url, id } = await imageUploader.upload(input.files[0]);

        editor.value
            ?.chain()
            .focus()
            .setImage({ src: url, id } as any)
            .run();
    } catch (err) {
        error.value = err instanceof Error ? err.message : (err as string);
    } finally {
        loading.value = false;
    }
}

watchOnce(editor, (editor) => {
    if (!imageUploader) {
        return;
    }

    editor?.on('delete', (event) => {
        if ('node' in event) {
            const { node } = event;

            if (node.type.name !== 'image') {
                return;
            }

            const id = node.attrs.id;
            imageUploader.delete(id);
        }
    });
});
</script>

<template>
    <Popover v-if="imageUploader">
        <PopoverTrigger as-child>
            <Button size="icon" variant="ghost">
                <ImagePlus class="size-4" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="flex w-72 flex-col gap-3 rounded-xl">
            <Label
                class="flex cursor-pointer flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-border p-6 text-center transition-colors hover:bg-muted"
            >
                <template v-if="loading">
                    <Spinner class="size-5" />
                    <span class="text-sm text-muted-foreground">
                        Enviando imagem...
                    </span>
                </template>
                <template v-else>
                    <div
                        class="flex size-10 items-center justify-center rounded-full bg-muted"
                    >
                        <Upload class="size-5 text-muted-foreground" />
                    </div>

                    <div class="text-sm text-muted-foreground">
                        <span class="underline">Clique para enviar</span> ou
                        arraste e solte
                        <div class="mt-1 text-xs opacity-70">
                            Arquivos de máximo 2MB
                        </div>
                    </div>
                </template>
                <Input
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="handleImageUpload($event.target)"
                />
            </Label>
            <div class="text-center"><InputError :message="error" /></div>
        </PopoverContent>
    </Popover>
</template>
