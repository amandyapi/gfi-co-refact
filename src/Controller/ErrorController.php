<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;

class ErrorController extends AbstractController
{
    public function error(Request $request, Environment $twig): Response
    {
        $exception = $request->attributes->get('exception');
        
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }
        
        $locale = $request->getLocale() ?? 'fr';
        
        // Vérifier si un template spécifique existe
        $template = sprintf('bundles/TwigBundle/Exception/error%d.html.twig', $statusCode);
        
        try {
            // Essayer de charger le template spécifique
            $twig->load($template);
        } catch (\Twig\Error\LoaderError $e) {
            // Sinon utiliser le template générique
            $template = 'bundles/TwigBundle/Exception/error.html.twig';
        }
        
        return $this->render($template, [
            'status_code' => $statusCode,
            'status_text' => Response::$statusTexts[$statusCode] ?? 'Erreur',
        ]);
    }
}