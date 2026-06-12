<?php
// src/EventListener/GalleryImageListener.php

namespace App\EventListener;

use App\Entity\GalleryImage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\File;

#[AsEntityListener(event: Events::prePersist, entity: GalleryImage::class)]
#[AsEntityListener(event: Events::preUpdate, entity: GalleryImage::class)]
class GalleryImageListener
{
    public function prePersist(GalleryImage $image, LifecycleEventArgs $args): void
    {
        $this->updateImageMetadata($image);
    }

    public function preUpdate(GalleryImage $image, LifecycleEventArgs $args): void
    {
        $this->updateImageMetadata($image);
    }

    private function updateImageMetadata(GalleryImage $image): void
    {
        $file = $image->getImageFile();
        
        if ($file instanceof File && $file->getPathname()) {
            // Remplir les métadonnées à partir du fichier
            $image->setOriginalName($file->getClientOriginalName());
            $image->setMimeType($file->getMimeType());
            $image->setFileSize($file->getSize());
            
            // Récupérer les dimensions de l'image
            try {
                $imageInfo = getimagesize($file->getPathname());
                if ($imageInfo) {
                    $image->setWidth($imageInfo[0]);
                    $image->setHeight($imageInfo[1]);
                }
            } catch (\Exception $e) {
                // Ignorer les erreurs de dimensions
            }
        }
    }
}