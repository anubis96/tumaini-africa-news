<?php
// src/Service/ImageMetadataService.php

namespace App\Service;

use App\Entity\GalleryImage;
use Symfony\Component\HttpFoundation\File\File;

class ImageMetadataService
{
    public function fillMetadata(GalleryImage $image): void
    {
        $file = $image->getImageFile();
        
        if (!$file instanceof File || !$file->getPathname()) {
            return;
        }
        
        // Vérifier que le fichier existe
        if (!file_exists($file->getPathname())) {
            return;
        }
        
        // Remplir les métadonnées de base
        $image->setOriginalName($file->getClientOriginalName());
        $image->setMimeType($file->getMimeType());
        $image->setFileSize($file->getSize());
        
        // Remplir les dimensions si c'est une image
        if (str_starts_with($file->getMimeType(), 'image/')) {
            $imageInfo = @getimagesize($file->getPathname());
            if ($imageInfo !== false) {
                $image->setWidth($imageInfo[0]);
                $image->setHeight($imageInfo[1]);
            }
        }
    }
}