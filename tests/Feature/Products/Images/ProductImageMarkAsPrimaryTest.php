<?php

namespace Tests\Feature\Products\Images;

use App\Enums\Role;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\HasProductRoleProviders;
use Tests\TestCase;

class ProductImageMarkAsPrimaryTest extends TestCase
{
    use HasProductRoleProviders, RefreshDatabase;

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_mark_image_as_primary(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();
        $image = ProductImage::factory()->for($product)->create();

        $this->actingAs($user)
            ->post(route('images.primary', [
                'product' => $product,
                'image' => $image,
            ]))->assertForbidden();
    }

    #[DataProvider('allowedRolesProvider')]
    public function test_users_with_permission_can_mark_image_as_primary(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();
        $image = ProductImage::factory()->for($product)->create();

        $this->actingAs($user)
            ->post(route('images.primary', [
                'product' => $product,
                'image' => $image,
            ]))
            ->assertOk()
            ->assertJson(['is_primary' => true]);
    }

    public function test_returns_forbidden_when_image_doesnt_correspond_to_product()
    {
        /** @var User $user */
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create();
        $image = ProductImage::factory()->create();

        $this->actingAs($user)
            ->post(route('images.primary', [
                'product' => $product,
                'image' => $image,
            ]))
            ->assertForbidden();
    }
}
