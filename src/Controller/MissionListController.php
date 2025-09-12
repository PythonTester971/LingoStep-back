<?php

namespace App\Controller;

use App\Repository\MissionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MissionListController extends AbstractController
{
    #[Route('/mission-all', name: 'missions_list', methods: ['GET'])]
    public function list(MissionRepository $missionRepository): Response
    {
        $missions = $missionRepository->findAll();

        return $this->render('mission_list/index.html.twig', [
            'missions' => $missions,
        ]);
    }
}
