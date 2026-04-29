<?php

namespace Tests\Feature\Products;

use App\Enums\ProductSize;
use App\Enums\Role;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\HasProductRoleProviders;
use Tests\TestCase;

class ProductCreateTest extends TestCase
{
    use HasProductRoleProviders, RefreshDatabase;

    #[DataProvider('allowedRolesProvider')]
    public function test_users_with_permission_can_access_create_product_view(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);

        $this->actingAs($user)
            ->get(route('products.create'))
            ->assertOk();
    }

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_access_create_product_view(
        Role $role
    ) {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);

        $response = $this->actingAs($user)
            ->get(route('products.create'))
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
    public function test_users_with_permission_can_create_product(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $payload = [
            'name' => fake()->name(),
            'stock' => fake()->numberBetween(0, 100),
            'price' => fake()->randomFloat(2, 1, 100),
            'sizes' => fake()->randomElements(ProductSize::cases()),
        ];

        $response = $this->actingAs($user)
            ->post(route('products.store'), $payload)
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();
        $product = Product::query()->latest()->first();

        $this->assertNotNull($product);
        $response->assertRedirect(route('products.edit', ['product' => $product]));
    }

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_create_product(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);

        $this->actingAs($user)
            ->post(route('products.store'))
            ->assertForbidden();
    }

    public function test_cannot_create_product_with_no_body()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->post(route('products.store'));
        $response->assertSessionHasErrors(['name', 'stock', 'price']);
    }

    public function test_cannot_create_product_with_invalid_data()
    {
        $user = User::factory()->admin()->create();
        $payload = [
            'name' => '',
            'stock' => -10,
            'price' => -5.99,
        ];

        $this->actingAs($user)
            ->post(route('products.store'), $payload)
            ->assertSessionHasErrors(['name', 'stock', 'price']);
    }

    public function test_generates_correct_sku()
    {
        Str::createRandomStringsUsing(fn () => '12345');

        $user = User::factory()->admin()->create();
        $payload = [
            'name' => 'Café',
            'stock' => 10,
            'price' => 3.99,
        ];

        $this->actingAs($user)->post(route('products.store'), $payload);
        $product = Product::first();

        $this->assertEquals('CAF-12345', $product->sku);

        Str::createRandomStringsNormally();
    }
}
