<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminOptionController extends AbstractController
{
    #[Route('/admin/option', name: 'app_admin_option')]
    public function index(): Response
    {
        return $this->render('admin_option/index.html.twig', [
            'controller_name' => 'AdminOptionController',
        ]);
    }
}
