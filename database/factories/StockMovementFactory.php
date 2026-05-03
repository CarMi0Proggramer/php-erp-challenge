<?php

namespace Database\Factories;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'reason' => $this->faker->sentence(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'type' => $this->faker->randomElements(
                [
                    StockMovementType::ADJUSTMENT->value,
                    StockMovementType::SALE->value,
                    StockMovementType::PURCHASE->value,
                ],
                $this->faker->numberBetween(1, 3)
            ),
        ];
    }

    public function withoutUser()
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
        ]);
    }
}
