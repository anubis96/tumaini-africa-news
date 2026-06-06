<?php
// src/Twig/AppExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('date_fr', [$this, 'formatDateFr']),
        ];
    }

    public function formatDateFr($date, $format = 'full'): string
    {
        if (!$date instanceof \DateTimeInterface) {
            $date = new \DateTime($date);
        }

        $formats = [
            'full' => 'EEEE d MMMM y',
            'long' => 'd MMMM y',
            'medium' => 'd MMM y',
            'short' => 'dd/MM/y',
            'day_month' => 'd MMMM',
            'day_month_year' => 'd MMMM y',
        ];

        $formatter = new \IntlDateFormatter(
            'fr_FR',
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            null,
            null,
            $formats[$format] ?? $format
        );

        return $formatter->format($date);
    }
}