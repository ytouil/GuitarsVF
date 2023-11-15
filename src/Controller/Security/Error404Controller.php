<?php


namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class Error404Controller extends AbstractController
{
    public function show(\Throwable $exception): Response
    {
        // Customize your response based on the exception or status code
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($statusCode == Response::HTTP_NOT_FOUND) {
            // Render your custom 404 template
            return $this->render('components/error404.html.twig');
        }



        return new Response('An error occurred', $statusCode);
    }
}