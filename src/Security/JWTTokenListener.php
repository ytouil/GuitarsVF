<?php

namespace App\Security;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class JWTTokenListener
{
public function onKernelRequest(RequestEvent $event): void
{
$request = $event->getRequest();
$jwtCookie = $request->cookies->get('JWT');

if ($jwtCookie) {
$request->headers->set('Authorization', 'Bearer ' . $jwtCookie);
}
}
}