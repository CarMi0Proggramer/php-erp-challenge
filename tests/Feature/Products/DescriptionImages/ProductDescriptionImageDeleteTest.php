<?php

namespace Tests\Feature\Products\DescriptionImages;

use App\Enums\Role;
use App\Models\Product;
use App\Models\ProductDescriptionImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\HasProductRoleProviders;
use Tests\TestCase;

class ProductDescriptionImageDeleteTest extends TestCase
{
    use HasProductRoleProviders, RefreshDatabase;

    private ?string $productId = null;

    private ?string $imageId = null;

    private ?string $imagePath = null;

    #[DataProvider('forbiddenRolesProvider')]
    public function test_users_without_permission_cannot_delete_description_images(
        Role $role
    ) {
        $user = User::factory()->create(['role' => $role->value]);

        $this->actingAs($user)
            ->delete(route('description-images.destroy', [
                'description_image' => $this->imageId,
                'product' => $this->productId,
            ]))
            ->assertForbidden();

        Storage::disk('public')->assertExists($this->imagePath);
    }

    #[DataProvider('allowedRolesProvider')]
    public function test_users_with_permission_can_delete_description_images(Role $role)
    {
        $user = User::factory()->create(['role' => $role->value]);

        $this->actingAs($user)
            ->delete(route('description-images.destroy', [
                'description_image' => $this->imageId,
                'product' => $this->productId,
            ]))
            ->assertOk()
            ->assertExactJson(['message' => 'Imagem deletada com sucesso']);

        Storage::disk('public')->assertMissing($this->imagePath);
        $this->assertNull(ProductDescriptionImage::find($this->imageId));
    }

    public function test_returns_forbidden_when_image_doesnt_correspond_to_product()
    {
        $user = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->delete(route('description-images.destroy', [
                'description_image' => $this->imageId,
                'product' => $product->id,
            ]))->assertForbidden();

        Storage::disk('public')->assertExists($this->imagePath);
    }

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $user = User::factory()->admin()->create();
        $image = UploadedFile::fake()->image('photo.png');
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('description-images.store', [
                'product' => $product,
            ]), ['image' => $image]);

        $this->imageId = $response->json('id');
        $this->productId = $product->id;
        $this->imagePath = $image->hashName('products/description-images');
    }
}
