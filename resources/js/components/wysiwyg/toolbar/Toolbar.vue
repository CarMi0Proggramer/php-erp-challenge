<script setup lang="ts">
import { Separator } from '@/components/ui/separator';
import {
    AlignCenter,
    AlignJustify,
    AlignLeft,
    AlignRight,
    Blockquote,
    Bold,
    BulletList,
    Headings,
    Highlight,
    Image,
    Italic,
    Link,
    OrderedList,
    Redo,
    Strike,
    Underline,
    Undo,
} from './commands';
</script>
<template>
    <div class="flex flex-wrap items-center border-b pb-2">
        <Undo />
        <Redo />
        <Separator orientation="vertical" class="mx-1 h-6!" />
        <Bold />
        <Italic />
        <Underline />
        <Strike />
        <Blockquote />
        <Headings />
        <BulletList />
        <OrderedList />
        <Highlight />
        <Link />
        <Image />
        <Separator orientation="vertical" class="mx-1 h-6!" />
        <AlignLeft />
        <AlignCenter />
        <AlignRight />
        <AlignJustify />
    </div>
</template>
