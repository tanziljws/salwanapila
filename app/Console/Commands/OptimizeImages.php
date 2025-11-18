<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize {--quality=85 : JPEG quality (1-100)} {--max-width=1920 : Maximum width in pixels}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize images in public/images directory by compressing and resizing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!extension_loaded('gd')) {
            $this->error('GD extension is not loaded. Please install php-gd extension.');
            return Command::FAILURE;
        }

        $quality = (int) $this->option('quality');
        $maxWidth = (int) $this->option('max-width');
        $imagesPath = public_path('images');
        
        if (!File::exists($imagesPath)) {
            $this->error("Images directory not found: {$imagesPath}");
            return Command::FAILURE;
        }

        $this->info("Starting image optimization...");
        $this->info("Quality: {$quality}%, Max Width: {$maxWidth}px");
        $this->newLine();

        $totalSaved = 0;
        $totalProcessed = 0;
        $totalErrors = 0;

        // Process images in main directory
        $this->processDirectory($imagesPath, $quality, $maxWidth, $totalSaved, $totalProcessed, $totalErrors);

        // Process images in subdirectories (like jurusan/)
        $subdirs = File::directories($imagesPath);
        foreach ($subdirs as $subdir) {
            $this->processDirectory($subdir, $quality, $maxWidth, $totalSaved, $totalProcessed, $totalErrors);
        }

        $this->newLine();
        $this->info("Optimization complete!");
        $this->info("Processed: {$totalProcessed} images");
        $this->info("Total space saved: " . $this->formatBytes($totalSaved));
        if ($totalErrors > 0) {
            $this->warn("Errors: {$totalErrors} images");
        }

        return Command::SUCCESS;
    }

    private function processDirectory($directory, $quality, $maxWidth, &$totalSaved, &$totalProcessed, &$totalErrors)
    {
        $files = File::files($directory);
        
        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());
            
            // Only process image files
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                continue;
            }

            $filePath = $file->getPathname();
            $originalSize = filesize($filePath);
            
            try {
                $this->line("Processing: " . $file->getFilename());
                
                // Load image based on type
                $image = null;
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        $image = imagecreatefromjpeg($filePath);
                        break;
                    case 'png':
                        $image = imagecreatefrompng($filePath);
                        break;
                    case 'gif':
                        $image = imagecreatefromgif($filePath);
                        break;
                }

                if (!$image) {
                    $this->warn("  ⚠️  Could not load image");
                    $totalErrors++;
                    continue;
                }

                $originalWidth = imagesx($image);
                $originalHeight = imagesy($image);
                
                // Calculate new dimensions if needed
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
                
                if ($originalWidth > $maxWidth) {
                    $newWidth = $maxWidth;
                    $newHeight = (int) ($originalHeight * ($maxWidth / $originalWidth));
                }

                // Create new image if resizing needed
                if ($newWidth != $originalWidth || $newHeight != $originalHeight) {
                    $newImage = imagecreatetruecolor($newWidth, $newHeight);
                    
                    // Preserve transparency for PNG
                    if ($extension === 'png') {
                        imagealphablending($newImage, false);
                        imagesavealpha($newImage, true);
                        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
                    }
                    
                    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
                    imagedestroy($image);
                    $image = $newImage;
                }

                // Create backup
                $backupPath = $filePath . '.backup';
                if (!File::exists($backupPath)) {
                    File::copy($filePath, $backupPath);
                }

                // Save optimized image
                $saved = false;
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        $saved = imagejpeg($image, $filePath, $quality);
                        break;
                    case 'png':
                        // PNG compression (0-9, where 9 is highest compression)
                        $pngQuality = 9 - (int)(($quality / 100) * 9);
                        $saved = imagepng($image, $filePath, $pngQuality);
                        break;
                    case 'gif':
                        $saved = imagegif($image, $filePath);
                        break;
                }

                imagedestroy($image);

                if ($saved) {
                    $newSize = filesize($filePath);
                    $savedBytes = $originalSize - $newSize;
                    $totalSaved += $savedBytes;
                    $totalProcessed++;
                    
                    if ($savedBytes > 0) {
                        $this->info("  ✅ Saved: " . $this->formatBytes($savedBytes) . " ({$newWidth}x{$newHeight})");
                    } else {
                        $this->line("  ℹ️  No optimization needed");
                    }
                } else {
                    $this->warn("  ⚠️  Failed to save optimized image");
                    $totalErrors++;
                }
            } catch (\Exception $e) {
                $this->error("  ❌ Error: " . $e->getMessage());
                $totalErrors++;
            }
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
