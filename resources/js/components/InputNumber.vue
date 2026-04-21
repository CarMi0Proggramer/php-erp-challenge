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
    defaultValue?: number;
    min?: number;
    max?: number;
    step?: number;
    label?: string;
    placeholder?: string;
};

type Emits = {
    (event: 'update:modelValue', value: number): void;
};

defineProps<Props>();
defineEmits<Emits>();
defineModel<number>();
</script>
<template>
    <NumberField
        :id="id"
        :min="min"
        :max="max"
        :step="step"
        :default-value="defaultValue"
        :model-value="modelValue"
        @update:model-value="$emit('update:modelValue', $event)"
    >
        <Label :for="id" v-if="label">{{ label }}</Label>
        <NumberFieldContent>
            <NumberFieldDecrement />
            <NumberFieldInput :placeholder="placeholder" />
            <NumberFieldIncrement />
        </NumberFieldContent>
    </NumberField>
</template>
