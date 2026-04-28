<?php

namespace App\Concerns;

use App\Enums\ProductSize;
use App\Models\Product;
use Illuminate\Validation\Rule;

trait ProductValidationRules
{
    public function nameRules()
    {
        return ['required', 'string', 'max:255'];
    }

    public function skuRules(?string $productId = null)
    {
        return [
            'required',
            'string',
            'max:255',
            $productId === null
              ? Rule::unique(Product::class, 'sku')
              : Rule::unique(Product::class, 'sku')->ignore($productId),
        ];
    }

    public function descriptionRules()
    {
        return ['nullable', 'string'];
    }

    public function priceRules()
    {
        return ['required', 'decimal:2', 'gt:0'];
    }

    public function stockRules()
    {
        return ['required', 'integer', 'min:0'];
    }

    public function sizesRules()
    {
        return [
            'sizes' => ['nullable', 'array'],
            'sizes.*' => Rule::enum(ProductSize::class),
        ];
    }
}
