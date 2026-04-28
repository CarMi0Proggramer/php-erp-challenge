<?php

use App\Enums\Role;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\HasProductRoleProviders;
use Tests\TestCase;

class ProductUpdateTest extends TestCase
{
    use HasProductRoleProviders, RefreshDatabase;

    #[DataProvider('allowedRolesProvider')]
    public function test_users_with_permission_can_access_edit_product_view(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->get(route('products.edit', ['product' => $product]))
            ->assertOk();
    }

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_access_edit_product_view(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('products.edit', ['product' => $product]))
            ->assertRedirect()
            ->assertSessionHas(
                'error',
                'Você não tem permissão para entrar nesta seção.'
            );

        $this->followRedirects($response)
            ->assertInertia(function (Assert $page) {
                $page->where(
                    'flash.error',
                    'Você não tem permissão para entrar nesta seção.'
                );
            });
    }

    #[DataProvider('allowedRolesProvider')]
    public function test_users_with_permission_can_update_product(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();

        $payload = [
            'name' => 'Updated Product Name',
            'stock' => 50,
            'price' => 99.99,
            'sku' => 'ABC-12345',
        ];

        $response = $this->actingAs($user)
            ->patch(route('products.update', ['product' => $product]), $payload);

        $product->refresh();

        $this->assertEquals('Updated Product Name', $product->name);
        $this->assertEquals(50, $product->stock);
        $this->assertEquals(99.99, $product->price);
        $this->assertEquals('ABC-12345', $product->sku);

        $response->assertRedirect()
            ->assertSessionHas('message', 'Produto atualizado com sucesso');
    }

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_update_product(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->patch(route('products.update', ['product' => $product]))
            ->assertForbidden();
    }

    public function test_cannot_update_product_with_no_body()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->patch(
            route(
                'products.update',
                ['product' => $product]
            )
        );

        $response->assertSessionHasErrors(['name', 'stock', 'price', 'sku']);
    }

    public function test_cannot_update_product_with_invalid_data()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $payload = [
            'name' => '',
            'stock' => -10,
            'price' => -5.99,
            'sku' => '',
        ];

        $response = $this->actingAs($user)
            ->patch(route('products.update', ['product' => $product]), $payload);

        $response->assertSessionHasErrors(['name', 'stock', 'price', 'sku']);
    }

    public function test_returns_404_when_product_does_not_exist()
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->patch(route('products.update', ['product' => 999999]), [])
            ->assertNotFound();
    }
}
