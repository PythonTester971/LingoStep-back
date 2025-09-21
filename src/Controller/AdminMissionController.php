<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminMissionController extends AbstractController
{
    #[Route('/admin/mission', name: 'app_admin_mission')]
    public function index(): Response
    {
        return $this->render('admin_mission/index.html.twig', [
            'controller_name' => 'AdminMissionController',
        ]);
    }
}
