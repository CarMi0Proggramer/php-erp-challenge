<?php

namespace Tests\Unit\Observers;

use App\Jobs\DeleteProductImagesJob;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProductObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_dispatches_job_on_force_delete()
    {
        Queue::fake();

        $product = Product::factory()
            ->hasImages(2)
            ->hasDescriptionImages(2)
            ->create();

        $product->forceDelete();

        Queue::assertPushed(DeleteProductImagesJob::class);
    }
}
