<?php


namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Error404Controller extends AbstractController
{
    public function show(\Throwable $exception): Response
    {
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($statusCode == Response::HTTP_NOT_FOUND) {
            return $this->render('components/error404.html.twig');
        }

        return new Response('An error occurred', $statusCode);
    }
}