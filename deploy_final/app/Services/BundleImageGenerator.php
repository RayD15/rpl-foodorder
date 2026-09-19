<?php

namespace App\Services;

use App\Models\Bundle;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;

class BundleImageGenerator
{
    /**
     * Maximum number of products to include in the collage.
     */
    public const MAX_PRODUCTS = 6;

    /**
     * Get the appropriate image driver.
     */
    private function getDriver(): ?string
    {
        if (extension_loaded('imagick')) {
            return ImagickDriver::class;
        }

        if (extension_loaded('gd')) {
            return GdDriver::class;
        }

        return null;
    }

    /**
     * Thumbnail size for each product image in the collage.
     */
    public const THUMB_SIZE = 200;

    /**
     * Number of columns in the collage grid.
     */
    public const COLUMNS = 2;

    /**
     * Background color for the collage canvas.
     */
    public const BG_COLOR = '#ffffff';

    /**
     * Padding between images in pixels.
     */
    public const PADDING = 10;

    /**
     * Generate a collage image from bundle's products.
     *
     * @return string|null Path to the generated image (relative from public/images/)
     */
    public function generateFromBundle(Bundle $bundle, bool $forceRegenerate = false): ?string
    {
        $products = $this->getBundleProducts($bundle);

        if ($products->isEmpty()) {
            Log::warning('Cannot generate bundle image: no products with valid images found.');
            return null;
        }

        // Limit to MAX_PRODUCTS
        $products = $products->take(self::MAX_PRODUCTS);

        $driver = $this->getDriver();
        if (! $driver) {
            Log::warning('Cannot generate bundle image: GD or Imagick extension not installed.');
            return null;
        }

        try {
            $manager = new ImageManager($driver);
            $canvas = $this->createCollageCanvas($products, $manager);
            $filename = $this->saveCanvas($canvas, $bundle);

            return $filename;
        } catch (\Exception $e) {
            Log::error('Failed to generate bundle image: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get all products from a bundle with their images.
     */
    private function getBundleProducts(Bundle $bundle): Collection
    {
        return $bundle->items
            ->map(fn ($item) => $item->product)
            ->filter(fn ($product) => $product && $product->image && $this->getImagePath($product->image) !== null);
    }

    /**
     * Debug method to check image paths.
     */
    public function debugImagePaths(Bundle $bundle): array
    {
        $results = [];
        foreach ($bundle->items as $item) {
            $product = $item->product;
            $path = $this->getImagePath($product->image ?? '');
            $exists = file_exists($path);
            $results[] = [
                'product' => $product->name,
                'image' => $product->image,
                'path' => $path,
                'exists' => $exists,
            ];
        }
        return $results;
    }

    /**
     * Get full path for an image.
     * Checks multiple possible locations for the image file.
     */
    private function getImagePath(string $image): ?string
    {
        if (! $image) {
            return null;
        }

        // Try public/images/{image}
        $publicPath = public_path('images/'.$image);
        if (file_exists($publicPath)) {
            return $publicPath;
        }

        // Try public/images/products/{image} (if image doesn't already have path)
        if (! str_contains($image, '/')) {
            $publicProductsPath = public_path('images/products/'.$image);
            if (file_exists($publicProductsPath)) {
                return $publicProductsPath;
            }
        }

        // If the image path starts with 'products/', also check storage
        if (str_starts_with($image, 'products/')) {
            $storagePath = storage_path('app/public/'.$image);
            if (file_exists($storagePath)) {
                return $storagePath;
            }
            // Also check public with products/ prefix
            $publicWithPrefix = public_path('images/'.$image);
            if (file_exists($publicWithPrefix)) {
                return $publicWithPrefix;
            }
        }

        return null;
    }

    /**
     * Create a collage canvas from product images.
     */
    private function createCollageCanvas(Collection $products, ImageManager $manager)
    {
        $productCount = $products->count();
        $rows = ceil($productCount / self::COLUMNS);

        // Calculate canvas dimensions
        $canvasWidth = (self::THUMB_SIZE * self::COLUMNS) + (self::PADDING * (self::COLUMNS - 1));
        $canvasHeight = (self::THUMB_SIZE * $rows) + (self::PADDING * ($rows - 1));

        $canvas = $manager->canvas($canvasWidth, $canvasHeight, self::BG_COLOR);

        $index = 0;
        foreach ($products as $product) {
            $imagePath = $this->getImagePath($product->image);

            // Create image from file
            $img = $manager->read($imagePath);

            // Resize while maintaining aspect ratio
            $img = $img->resize(self::THUMB_SIZE, self::THUMB_SIZE, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Calculate position
            $row = floor($index / self::COLUMNS);
            $col = $index % self::COLUMNS;

            // Insert image onto canvas (centered within the cell)
            $imgWidth = $img->width();
            $imgHeight = $img->height();

            $offsetX = ($col * self::THUMB_SIZE) + (self::THUMB_SIZE - $imgWidth) / 2 + ($col * self::PADDING);
            $offsetY = ($row * self::THUMB_SIZE) + (self::THUMB_SIZE - $imgHeight) / 2 + ($row * self::PADDING);

            $canvas = $canvas->place($img, 'top-left', (int) $offsetX, (int) $offsetY);

            $index++;
        }

        return $canvas;
    }

    /**
     * Save the canvas to a file.
     */
    private function saveCanvas($canvas, Bundle $bundle): string
    {
        // Delete old image if exists
        $this->deleteExistingImage($bundle);

        $filename = Str::uuid().'.jpg';
        $path = 'products/'.$filename;
        $canvas->save(storage_path('app/public/'.$path), 90, 'jpg');

        return $path;
    }

    /**
     * Delete the existing bundle image if it exists.
     */
    private function deleteExistingImage(Bundle $bundle): void
    {
        if (! $bundle->image) {
            return;
        }

        // Hapus dari storage/app/public
        $storagePath = storage_path('app/public/'.$bundle->image);
        if (file_exists($storagePath)) {
            unlink($storagePath);
        }

        // Also check old path for backward compatibility
        if (str_contains($bundle->image, '/')) {
            $oldPublicPath = public_path('images/'.$bundle->image);
            if (file_exists($oldPublicPath)) {
                unlink($oldPublicPath);
            }
        }
    }

    /**
     * Generate a placeholder image for bundles with no products.
     */
    public function generatePlaceholder(): string
    {
        $driver = $this->getDriver();
        $manager = new ImageManager($driver);
        $canvas = $manager->canvas(400, 400, '#f3f4f6');

        // Add a simple "Bundle" text or icon
        // For now, just return a blank placeholder

        $filename = 'placeholder-bundle-'.Str::uuid().'.jpg';
        $directory = public_path('images/products');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filepath = $directory.'/'.$filename;
        $canvas->save($filepath, 90, 'jpg');

        return 'products/'.$filename;
    }
}
