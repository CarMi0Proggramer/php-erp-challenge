<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ProductFilter extends ModelFilter
{
    public function searchTerm(string $searchTerm)
    {
        return $this->whereAny(['name', 'sku'], 'like', "%{$searchTerm}%");
    }

    public function priceFrom(int $priceFrom)
    {
        return $this->where('price', '>=', $priceFrom);
    }

    public function priceTo(int $priceTo)
    {
        return $this->where('price', '<=', $priceTo);
    }

    public function sizes(array $sizes)
    {
        return $this->whereNotNull('sizes')
            ->whereJsonContains('sizes', $sizes);
    }

    public function stockFrom(int $stockFrom)
    {
        return $this->where('stock', '>=', $stockFrom);
    }

    public function stockTo(int $stockTo)
    {
        return $this->where('stock', '<=', $stockTo);
    }

    public function sortBy(string $sortBy)
    {
        $allowedFields = collect(['name', 'sku', 'price', 'stock']);
        if ($allowedFields->doesntContain($sortBy)) {
            return $this;
        }

        $direction = request()->query('sortDirection', 'asc');

        return $this->orderBy($sortBy, $direction);
    }
}
