<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import StockMovementController from '@/actions/App/Http/Controllers/Products/StockMovementController';
import InputError from '@/components/InputError.vue';
import InputNumber from '@/components/InputNumber.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import type { Product } from '@/types';

type Props = {
    product: Product;
};

defineProps<Props>();
</script>
<template>
    <Card>
        <CardHeader>
            <span class="font-medium">Ajustar Stock</span>
        </CardHeader>

        <CardContent>
            <Form
                class="flex flex-col gap-6"
                reset-on-success
                v-bind="
                    StockMovementController.store.form({ product: product.id })
                "
                v-slot="{ errors, processing }"
                :options="{ preserveScroll: true }"
            >
                <div class="flex flex-col gap-2">
                    <Label for="balance" class="text-sm text-muted-foreground"
                        >Saldo Final</Label
                    >
                    <InputNumber
                        id="balance"
                        name="balance"
                        placeholder="Ex: 10"
                    />
                    <InputError :message="errors.balance" />
                </div>
                <div class="flex flex-col gap-2">
                    <Label for="reason" class="text-sm text-muted-foreground"
                        >Motivo</Label
                    >
                    <Input
                        id="reason"
                        name="reason"
                        placeholder="Ex: Ajuste, compra, venda..."
                    />
                    <InputError :message="errors.reason" />
                </div>
                <Button class="w-full" :disabled="processing">
                    <Spinner v-if="processing" />
                    Aplicar Ajuste
                </Button>
            </Form>
        </CardContent>
    </Card>
</template>
