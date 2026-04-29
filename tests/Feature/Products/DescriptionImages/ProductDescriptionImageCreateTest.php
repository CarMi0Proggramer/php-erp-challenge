<?php

namespace Tests\Feature\Products\DescriptionImages;

use App\Enums\Role;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\HasProductRoleProviders;
use Tests\TestCase;

class ProductDescriptionImageCreateTest extends TestCase
{
    use HasProductRoleProviders, RefreshDatabase;

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_create_description_images(
        Role $role
    ) {
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();
        $image = UploadedFile::fake()->image('photo.png');

        $this->actingAs($user)
            ->post(route('description-images.store', [
                'product' => $product,
            ], ['image' => $image]))
            ->assertForbidden();
    }

    #[DataProvider('allowedRolesProvider')]
    public function test_users_with_permission_can_create_description_images(Role $role)
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();
        $image = UploadedFile::fake()->image('photo.png');

        $this->actingAs($user)
            ->post(route('description-images.store', [
                'product' => $product,
            ]), ['image' => $image])
            ->assertOk()
            ->assertJsonStructure(['id', 'url']);

        Storage::disk('public')
            ->assertExists($image->hashName('products/description-images'));
    }
}
