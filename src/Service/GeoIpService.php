<?php
// src/Service/GeoIpService.php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeoIpService
{
    public function __construct(
        private HttpClientInterface $httpClient
    ) {}

    public function getLocation(?string $ip): array
    {
        $default = [
            'country' => 'Unknown',
            'countryCode' => 'XX',
            'city' => 'Unknown',
        ];

        // Ignorer les IPs locales
        if (!$ip || $ip === '127.0.0.1' || str_starts_with($ip, '192.168') || str_starts_with($ip, '10.0')) {
            return $default;
        }

        try {
            // API gratuite ip-api.com
            $response = $this->httpClient->request('GET', "http://ip-api.com/json/{$ip}", [
                'timeout' => 3,
            ]);
            
            $data = $response->toArray();
            
            if ($data['status'] === 'success') {
                return [
                    'country' => $data['country'] ?? 'Unknown',
                    'countryCode' => $data['countryCode'] ?? 'XX',
                    'city' => $data['city'] ?? 'Unknown',
                ];
            }
        } catch (\Exception $e) {
            // En cas d'erreur, retourner les valeurs par défaut
        }

        return $default;
    }
}