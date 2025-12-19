<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ImageHelper
{
    public static function validateImageDimensions(string $url,int $requiredWidth,int $requiredHeight): ?string
    {
        try {
            $response = Http::timeout(5)->get($url);

            if (!$response->successful()) {
                return 'Unable to download image from the provided URL.';
            }

            $tempPath = tempnam(sys_get_temp_dir(), 'img_');
            file_put_contents($tempPath, $response->body());

            $imageInfo = getimagesize($tempPath);

            if ($imageInfo === false) {
                unlink($tempPath);
                return 'The provided URL does not contain a valid image.';
            }

            [$width, $height] = $imageInfo;

            unlink($tempPath);

            if ($width !== $requiredWidth || $height !== $requiredHeight) {
                return "Image dimensions must be {$requiredWidth}x{$requiredHeight}px.";
            }

            return null;

        } catch (\Exception $e) {
            return 'Failed to validate image from the provided URL.';
        }
    }
}
