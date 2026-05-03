<?php

namespace Tests\Feature\Products\Stock;

use App\Enums\Role;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\HasProductRoleProviders;
use Tests\TestCase;

class ProductStockMovementCreateTest extends TestCase
{
    use HasProductRoleProviders, RefreshDatabase;

    #[DataProvider('allowedRolesProvider')]
    public function test_users_with_permission_can_create_stock_movements(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();
        $payload = [
            'balance' => 10,
            'reason' => 'Restante de produto achado no armazém',
        ];

        $this->actingAs($user)
            ->post(route('products.stock.store', ['product' => $product]), $payload)
            ->assertRedirect(route('products.stock', ['product' => $product]))
            ->assertSessionDoesntHaveErrors();

        $movement = StockMovement::query()->latest()->first();
        $this->assertNotNull($movement);
    }

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_create_stock_movements(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->post(route('products.stock.store', ['product' => $product]))
            ->assertForbidden();
    }

    public function test_cannot_create_stock_movement_with_no_body()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('products.stock.store', ['product' => $product]));

        $response->assertSessionHasErrors(['balance', 'reason']);
    }

    public function test_cannot_create_stock_movement_with_invalid_data()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create();
        $payload = [
            'balance' => -9,
            'reason' => '',
        ];

        $this->actingAs($user)
            ->post(route('products.stock.store', ['product' => $product]), $payload)
            ->assertSessionHasErrors(['balance', 'reason']);
    }
}
