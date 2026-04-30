<?php

namespace Tests\Feature\Products\Images;

use App\Enums\Role;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\HasProductRoleProviders;
use Tests\TestCase;

class ProductImageCreateTest extends TestCase
{
    use HasProductRoleProviders, RefreshDatabase;

    #[DataProvider('allowedRolesProvider')]
    public function test_users_with_permission_can_create_image(Role $role)
    {
        Storage::fake('public');

        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();
        $image = UploadedFile::fake()->image('photo.png');

        $this->actingAs($user)
            ->post(route('images.store', ['product' => $product]), [
                'image' => $image,
            ])
            ->assertCreated()
            ->assertJsonStructure([
                'id',
                'url',
                'path',
                'product_id',
                'created_at',
                'updated_at',
            ]);

        Storage::disk('public')
            ->assertExists($image->hashName('products/images'));
    }

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_create_image(Role $role)
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role->value]);
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->post(route('images.store', ['product' => $product]))
            ->assertForbidden();
    }
}
