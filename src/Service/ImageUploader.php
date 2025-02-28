<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploader
{
    private string $uploadDirectory;
    private SluggerInterface $slugger;

    public function __construct(string $uploadDirectory, SluggerInterface $slugger)
    {
        $this->uploadDirectory = $uploadDirectory;
        $this->slugger = $slugger;
    }

    public function uploadAndConvertToWebp(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.webp';

        $image = $this->createImageFromFile($file);
        if (!$image) {
            throw new \RuntimeException('Impossible de charger l\'image.');
        }

        $destinationPath = $this->uploadDirectory . '/' . $newFilename;
        imagewebp($image, $destinationPath, 80);
        imagedestroy($image);

        return $newFilename;
    }

    private function createImageFromFile(UploadedFile $file)
    {
        $imageInfo = getimagesize($file->getPathname());
        if (!$imageInfo) {
            throw new \RuntimeException('Impossible de lire l\'image.');
        }

        return match ($imageInfo[2]) {
            IMAGETYPE_GIF => imagecreatefromgif($file->getPathname()),
            IMAGETYPE_JPEG => imagecreatefromjpeg($file->getPathname()),
            IMAGETYPE_PNG => $this->processPng($file),
            default => throw new \RuntimeException('Format d\'image non pris en charge.'),
        };
    }

    private function processPng(UploadedFile $file)
    {
        $image = imagecreatefrompng($file->getPathname());
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        return $image;
    }
}
