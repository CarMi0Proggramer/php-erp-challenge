<?php

namespace Tests\Feature\Products;

use App\Enums\Role;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\HasProductRoleProviders;
use Tests\TestCase;

class ProductDeleteTest extends TestCase
{
    use HasProductRoleProviders, RefreshDatabase;

    #[DataProvider('allowedRolesProvider')]
    public function test_users_with_permission_can_delete_product(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();
        $productId = $product->id;

        $this->actingAs($user)
            ->delete(route('products.destroy', ['product' => $product]))
            ->assertRedirect()
            ->assertSessionHas('message', 'Produto deletado com sucesso');

        $this->assertNull(Product::find($productId));
    }

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_delete_product(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();
        $productId = $product->id;

        $this->actingAs($user)
            ->delete(route('products.destroy', ['product' => $product]))
            ->assertForbidden();

        $this->assertNotNull(Product::find($productId));
    }

    public function test_product_is_actually_deleted_from_database()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->assertDatabaseHas('products', ['id' => $product->id]);

        $this->actingAs($user)
            ->delete(route('products.destroy', ['product' => $product]));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_returns_404_when_product_does_not_exist()
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->delete(route('products.destroy', ['product' => 999999]))
            ->assertNotFound();
    }
}
