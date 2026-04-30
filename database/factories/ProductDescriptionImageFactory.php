<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductDescriptionImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductDescriptionImage>
 */
class ProductDescriptionImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => fake()->filePath(),
            'product_id' => Product::factory(),
        ];
    }
}
