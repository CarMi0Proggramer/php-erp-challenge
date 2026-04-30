<?php

namespace Tests\Unit\Jobs;

use App\Jobs\DeleteProductImagesJob;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteProductImagesJobTest extends TestCase
{
    public function test_deletes_images_from_storage()
    {
        Storage::fake('public');

        Storage::disk('public')->put('img1.jpg', 'test');
        Storage::disk('public')->put('img2.jpg', 'test');

        $job = new DeleteProductImagesJob([
            'img1.jpg',
            'img2.jpg',
        ]);

        $job->handle();

        Storage::disk('public')->assertMissing('img1.jpg');
        Storage::disk('public')->assertMissing('img2.jpg');
    }

    public function test_logs_error_when_delete_fails()
    {
        Storage::shouldReceive('disk->delete')->andThrow(new Exception('fail'));
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Error deleting image'
                  && $context['path'] === 'img1.jpg'
                  && isset($context['error']);
            });

        $job = new DeleteProductImagesJob(['img1.jpg']);
        $job->handle();
    }
}
