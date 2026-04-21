<?php

namespace Tests\Feature\Products;

use App\Enums\ProductSize;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_can_view_products()
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get(route('products'));

        $response->assertOk();
    }

    public function test_operators_can_view_products()
    {
        $operator = User::factory()->operator()->create();
        $response = $this->actingAs($operator)->get(route('products'));

        $response->assertOk();
    }

    public function test_non_admin_or_non_operator_cannot_view_products()
    {
        $seller = User::factory()->seller()->create();
        $accountant = User::factory()->accountant()->create();

        $this->actingAs($seller)
            ->get(route('products'))
            ->assertRedirect(route('dashboard'));

        $this->actingAs($accountant)
            ->get(route('products'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_can_list_users()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(5)->create();

        $this->actingAs($user)
            ->get(route('products'))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 5);
                $page->has('pagination', function (Assert $pagination) {
                    $pagination->where('total', 5)
                        ->where('perPage', 15)
                        ->where('currentPage', 1)
                        ->where('lastPage', 1);
                });
            });
    }

    public function test_can_search_products_by_name_or_sku()
    {
        $user = User::factory()->admin()->create();
        Product::factory()
            ->count(3)
            ->sequence(
                ['name' => 'Product 1', 'sku' => 'SKU001'],
                ['name' => 'Product 2', 'sku' => 'SKU002'],
                ['name' => 'Product 3', 'sku' => 'SKU003'],
            )->create();

        $this->actingAs($user)
            ->get(route('products', ['searchTerm' => 'Product 1']))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 1)
                    ->where('products.0.name', 'Product 1')
                    ->where('products.0.sku', 'SKU001')
                    ->etc();
            });

        $this->actingAs($user)
            ->get(route('products', ['searchTerm' => 'SKU002']))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 1)
                    ->where('products.0.name', 'Product 2')
                    ->where('products.0.sku', 'SKU002')
                    ->etc();
            });
    }

    public function test_can_order_products_by_name()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['name' => 'Product 2'],
                ['name' => 'Product 3'],
                ['name' => 'Product 1'],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'sortBy' => 'name',
                'sortDirection' => 'asc',
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 3)
                    ->where('products.0.name', 'Product 1')
                    ->where('products.2.name', 'Product 3')
                    ->etc();
            });
    }

    public function test_can_order_products_by_price()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['price' => 20],
                ['price' => 10],
                ['price' => 30],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'sortBy' => 'price',
                'sortDirection' => 'asc',
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 3)
                    ->where('products.0.price', 10)
                    ->where('products.2.price', 30)
                    ->etc();
            });
    }

    public function test_can_order_products_by_stock()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['stock' => 10],
                ['stock' => 5],
                ['stock' => 15],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'sortBy' => 'stock',
                'sortDirection' => 'asc',
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 3)
                    ->where('products.0.stock', 5)
                    ->where('products.2.stock', 15)
                    ->etc();
            });
    }

    public function test_can_order_products_by_sku()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['sku' => 'SKU002'],
                ['sku' => 'SKU001'],
                ['sku' => 'SKU003'],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'sortBy' => 'sku',
                'sortDirection' => 'asc',
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 3)
                    ->where('products.0.sku', 'SKU001')
                    ->where('products.2.sku', 'SKU003')
                    ->etc();
            });
    }

    public function test_cannot_order_products_by_invalid_field()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['name' => 'Product 3'],
                ['name' => 'Product 1'],
                ['name' => 'Product 2'],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'sortBy' => 'invalid_field',
                'sortDirection' => 'asc',
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 3)
                    ->where('products.0.name', 'Product 3')
                    ->where('products.1.name', 'Product 1')
                    ->where('products.2.name', 'Product 2')
                    ->etc();
            });
    }

    public function test_can_order_products_in_descending_order()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['name' => 'Product 2'],
                ['name' => 'Product 1'],
                ['name' => 'Product 3'],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'sortBy' => 'name',
                'sortDirection' => 'desc',
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 3)
                    ->where('products.0.name', 'Product 3')
                    ->where('products.2.name', 'Product 1')
                    ->etc();
            });
    }

    public function test_can_filter_products_from_a_price()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['price' => 10],
                ['price' => 20],
                ['price' => 30],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'priceFrom' => 15,
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 2)
                    ->where('products.0.price', 20)
                    ->where('products.1.price', 30)
                    ->etc();
            });
    }

    public function test_can_filter_products_to_a_price()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['price' => 10],
                ['price' => 20],
                ['price' => 30],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'priceTo' => 25,
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 2)
                    ->where('products.0.price', 10)
                    ->where('products.1.price', 20)
                    ->etc();
            });
    }

    public function test_can_filter_products_by_price_range()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['price' => 10],
                ['price' => 20],
                ['price' => 30],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'priceFrom' => 15,
                'priceTo' => 25,
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 1)
                    ->where('products.0.price', 20)
                    ->etc();
            });
    }

    public function test_can_filter_products_from_a_stock()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['stock' => 5],
                ['stock' => 10],
                ['stock' => 15],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'stockFrom' => 8,
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 2)
                    ->where('products.0.stock', 10)
                    ->where('products.1.stock', 15)
                    ->etc();
            });
    }

    public function test_can_filter_products_to_a_stock()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['stock' => 5],
                ['stock' => 10],
                ['stock' => 15],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'stockTo' => 12,
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 2)
                    ->where('products.0.stock', 5)
                    ->where('products.1.stock', 10)
                    ->etc();
            });
    }

    public function test_can_filter_products_by_stock_range()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['stock' => 5],
                ['stock' => 10],
                ['stock' => 15],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'stockFrom' => 8,
                'stockTo' => 12,
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 1)
                    ->where('products.0.stock', 10)
                    ->etc();
            });
    }

    public function test_can_filter_products_by_sizes()
    {
        $user = User::factory()->admin()->create();
        Product::factory()->count(3)
            ->sequence(
                ['sizes' => [ProductSize::S->value, ProductSize::M->value]],
                ['sizes' => [ProductSize::M->value, ProductSize::L->value]],
                ['sizes' => [ProductSize::L->value, ProductSize::XL->value]],
            )->create();

        $this->actingAs($user)
            ->get(route('products', [
                'sizes' => [ProductSize::M->value],
            ]))
            ->assertOk()
            ->assertInertia(function (Assert $page) {
                $page->has('products', 2)
                    ->where('products.0.sizes', [
                        ProductSize::S->value,
                        ProductSize::M->value,
                    ])
                    ->where('products.1.sizes', [
                        ProductSize::M->value,
                        ProductSize::L->value,
                    ])
                    ->etc();
            });
    }
}
