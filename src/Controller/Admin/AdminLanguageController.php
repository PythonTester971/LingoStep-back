<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminLanguageController extends AbstractController
{
    #[Route('/admin/language', name: 'app_admin_language')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin_language/index.html.twig', [
            'controller_name' => 'AdminLanguageController',
        ]);
    }
}
