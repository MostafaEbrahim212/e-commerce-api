<?php
// tests/Unit/UploadsImageTest.php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Traits\UploadsImage;

class UploadsImageTest extends TestCase
{
    use UploadsImage;

    /** @test */
    public function it_uploads_image_and_returns_path()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test-image.jpg');
        $request = Request::create('/upload', 'POST', [], [], ['image' => $file]);
        $filePath = $this->uploadImage($request, 'test-folder');
        Storage::disk('public')->assertExists('test-folder/' . basename($filePath));
        $this->assertStringContainsString('test-folder/', $filePath);
    }

    /** @test */
    public function it_returns_validation_error_when_no_image_provided()
    {
        $request = Request::create('/upload', 'POST', []);
        $response = $this->uploadImage($request, 'test-folder');
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
    }
}
