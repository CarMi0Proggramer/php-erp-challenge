<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'role' => Role::SELLER->value,
        ];
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }

    /**
     * Indicate that the model is admin
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::ADMIN->value,
        ]);
    }

    /**
     * Indicate that the model is accountant
     */
    public function accountant()
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::ACCOUNTANT->value,
        ]);
    }

    /**
     * Indicate that the model is operator
     */
    public function operator()
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::OPERATOR->value,
        ]);
    }

    /**
     * Indicate that the model is seller
     */
    public function seller()
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::SELLER->value,
        ]);
    }
}
