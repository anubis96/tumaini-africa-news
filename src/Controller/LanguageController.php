<?php
// src/Controller/LanguageController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LanguageController extends AbstractController
{
    #[Route('/change-language/{locale}', name: 'change_language')]
    public function changeLanguage(string $locale, Request $request): Response
    {
        // Vérifier si la locale est autorisée
        $allowedLocales = ['fr', 'en', 'sw'];
        
        if (!in_array($locale, $allowedLocales)) {
            $locale = 'fr';
        }
        
        // Stocker la locale en session
        $request->getSession()->set('_locale', $locale);
        
        // Rediriger vers la page précédente ou l'accueil
        $referer = $request->headers->get('referer');
        
        if ($referer) {
            // Remplacer la locale dans l'URL de redirection
            $referer = preg_replace('/\/(fr|en|sw)\//', '/' . $locale . '/', $referer);
            return $this->redirect($referer);
        }
        
        return $this->redirectToRoute('home', ['_locale' => $locale]);
    }
}