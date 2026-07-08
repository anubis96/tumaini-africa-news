<?php
// src/Twig/LocaleExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LocaleExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_locale', [$this, 'getCurrentLocale']),
            new TwigFunction('locale_name', [$this, 'getLocaleName']),
            new TwigFunction('available_locales', [$this, 'getAvailableLocales']),
        ];
    }
    
    public function getCurrentLocale(): string
    {
        return $this->requestStack->getCurrentRequest()?->getLocale() ?? 'fr';
    }
    
    public function getLocaleName(string $locale): string
    {
        $locales = [
            'fr' => 'Français',
            'en' => 'English',
            'sw' => 'Kiswahili'
        ];
        
        return $locales[$locale] ?? $locale;
    }
    
    public function getAvailableLocales(): array
    {
        return [
            'fr' => ['code' => 'fr', 'name' => 'Français', 'flag' => '🇫🇷'],
            'en' => ['code' => 'en', 'name' => 'English', 'flag' => '🇬🇧'],
            'sw' => ['code' => 'sw', 'name' => 'Kiswahili', 'flag' => '🇨🇩']
        ];
    }
}