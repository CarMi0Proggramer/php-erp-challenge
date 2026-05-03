<?php

namespace Tests\Feature\Products\Stock;

use App\Enums\Role;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\HasProductRoleProviders;
use Tests\TestCase;

class ProductStockMovementTest extends TestCase
{
    use HasProductRoleProviders, RefreshDatabase;

    #[DataProvider('allowedRolesProvider')]
    public function test_users_with_permission_can_access_movements_view(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->get(route('products.stock', ['product' => $product]))
            ->assertOk();
    }

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_access_movements_view(
        Role $role
    ) {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->get(route('products.stock', ['product' => $product]))
            ->assertRedirect(route('dashboard'));
    }

    public function test_can_paginate_stock_movements()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create();
        StockMovement::factory()->for($product)->count(10)->create();

        $this->actingAs($user)
            ->get(route('products.stock', [
                'product' => $product,
                'page' => 2,
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('stockMovements', 5);
                $page->has('pagination', function (Assert $pagination) {
                    $pagination->where('total', 10)
                        ->where('perPage', 5)
                        ->where('currentPage', 2)
                        ->where('lastPage', 2);
                });
            });
    }
}
