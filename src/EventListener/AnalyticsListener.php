<?php
// src/EventListener/AnalyticsListener.php

namespace App\EventListener;

use App\Entity\Analytics;
use App\Service\DeviceDetectorService;
use App\Service\GeoIpService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsEventListener(event: KernelEvents::RESPONSE)]
class AnalyticsListener
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack,
        private DeviceDetectorService $deviceDetector,
        private GeoIpService $geoIpService
    ) {}

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        
        // Ne pas tracker les requêtes admin ou assets
        $route = $request->attributes->get('_route');
        if (str_starts_with($route ?? '', 'admin') || str_starts_with($route ?? '', '_')) {
            return;
        }

        $session = $request->getSession();
        
        // Vérifier si la session existe
        if (!$session) {
            return;
        }
        
        $sessionId = $session->getId() ?? bin2hex(random_bytes(16));
        $userAgent = $request->headers->get('User-Agent');
        $ipAddress = $request->getClientIp();

        // Analyser l'User-Agent
        $deviceInfo = $this->deviceDetector->analyze($userAgent);
        
        // Obtenir la géolocalisation
        $geoInfo = $this->geoIpService->getLocation($ipAddress);

        $analytics = new Analytics();
        $analytics->setSessionId($sessionId);
        $analytics->setIpAdress($ipAddress);
        $analytics->setUserAgent($userAgent);
        $analytics->setDeviceType($deviceInfo['deviceType']);
        $analytics->setDeviceBrand($deviceInfo['deviceBrand']);
        $analytics->setOs($deviceInfo['os']);
        $analytics->setBrowser($deviceInfo['browser']);
        $analytics->setCountry($geoInfo['country']);
        $analytics->setCountryCode($geoInfo['countryCode']);
        $analytics->setCity($geoInfo['city']);
        $analytics->setPageUrl($request->getRequestUri());
        $analytics->setPageTitle($route ?? 'Unknown');
        $analytics->setReferrer($request->headers->get('referer'));
        $analytics->setVisitedAt(new \DateTimeImmutable());

        $this->entityManager->persist($analytics);
        $this->entityManager->flush();
    }
}