<?php
// src/Service/DeviceDetectorService.php

namespace App\Service;

use DeviceDetector\DeviceDetector;

class DeviceDetectorService
{
    public function analyze(?string $userAgent): array
    {
        if (!$userAgent) {
            return [
                'deviceType' => 'desktop',
                'deviceBrand' => null,
                'os' => 'unknown',
                'browser' => 'unknown',
            ];
        }

        try {
            $dd = new DeviceDetector($userAgent);
            $dd->parse();
            
            // Déterminer le type d'appareil
            $deviceType = $dd->getDeviceName();
            if ($deviceType) {
                $deviceType = strtolower($deviceType);
            } else {
                $deviceType = 'desktop';
            }
            
            // Obtenir le système d'exploitation
            $os = $dd->getOs();
            $osName = $os['name'] ?? 'unknown';
            
            // Obtenir le navigateur
            $client = $dd->getClient();
            $browser = $client['name'] ?? 'unknown';
            
            return [
                'deviceType' => $deviceType,
                'deviceBrand' => $dd->getBrandName(),
                'os' => $osName,
                'browser' => $browser,
            ];
        } catch (\Exception $e) {
            return [
                'deviceType' => 'desktop',
                'deviceBrand' => null,
                'os' => 'unknown',
                'browser' => 'unknown',
            ];
        }
    }
}