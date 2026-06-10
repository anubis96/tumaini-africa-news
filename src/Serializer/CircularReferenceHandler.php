<?php
// src/Serializer/CircularReferenceHandler.php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CircularReferenceHandler
{
    public function __invoke($object)
    {
        // Retourne l'ID de l'objet pour éviter la référence circulaire
        if (method_exists($object, 'getId')) {
            return ['id' => $object->getId()];
        }
        
        return null;
    }
}