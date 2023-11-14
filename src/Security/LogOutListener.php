<?php


namespace App\Security;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogOutListener
{
    /**
     * @throws \Exception
     */
    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $event): void
    {

        // Replace 'your_cookie_name' with the actual name of the cookie you want to clear
        $cookie = new Cookie('JWT', '', time() - 3600);
        $response = $event->getResponse();
        if (null === $response) {
            throw new \Exception('No response is set for the logout event.');
        }

        $response->headers->setCookie($cookie);

    }
}