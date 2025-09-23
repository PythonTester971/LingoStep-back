<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class FooterController extends AbstractController
{
    #[Route('/terms-of-service', name: 'app_terms_of_service')]
    public function termsOfService(): Response
    {
        return $this->render('footer/terms-of-service.html.twig');
    }

    #[Route('/privacy-policy', name: 'app_privacy_policy')]
    public function privacyPolicy(): Response
    {
        return $this->render('footer/privacy-policy.html.twig');
    }
}
