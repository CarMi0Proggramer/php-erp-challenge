<script setup lang="ts">
import { Label } from '@/components/ui/label';
import {
    NumberField,
    NumberFieldContent,
    NumberFieldIncrement,
    NumberFieldDecrement,
    NumberFieldInput,
} from '@/components/ui/number-field';

type Props = {
    id?: string;
    label?: string;
    placeholder?: string;
    name?: string;
};

type Emits = {
    (event: 'update:modelValue', value: number): void;
};

defineOptions({ inheritAttrs: false });
defineProps<Props>();
defineEmits<Emits>();
defineModel<number>();
</script>
<template>
    <NumberField
        v-bind="$attrs"
        :id="id"
        :model-value="modelValue"
        @update:model-value="$emit('update:modelValue', $event)"
    >
        <Label :for="id" v-if="label">{{ label }}</Label>
        <NumberFieldContent>
            <NumberFieldDecrement />
            <NumberFieldInput :placeholder="placeholder" :name="name" />
            <NumberFieldIncrement />
        </NumberFieldContent>
    </NumberField>
</template>
