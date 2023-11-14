<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class RoleBasedRedirectionListener
{
    private $router;
    private $security;

    public function __construct(RouterInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $user = $this->security->getUser();

        // Redirect unauthenticated users trying to access restricted areas to the login page
        if (!$user && preg_match('/^(\/admin|\/dashboard)/', $request->getPathInfo())) {
            $response = new RedirectResponse($this->router->generate('show_login_form'));
            $event->setResponse($response);
        }

        // Redirect authenticated users with ROLE_USER trying to access /admin to their profile
       if ($user && $this->security->isGranted('ROLE_USER') && str_starts_with($request->getPathInfo(), '/admin')) {
            $response = new RedirectResponse($this->router->generate('app_dashboardindex'));
            $event->setResponse($response);
        }



    }
}
