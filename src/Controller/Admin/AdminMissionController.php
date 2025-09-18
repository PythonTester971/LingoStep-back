<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminMissionController extends AbstractController
{
    #[Route('/admin/mission', name: 'app_admin_mission')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin_templates/admin_mission/index.html.twig', [
            'controller_name' => 'AdminMissionController',
        ]);
    }
}
