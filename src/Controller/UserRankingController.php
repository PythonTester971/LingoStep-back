<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserRankingController extends AbstractController
{
    #[Route('/user/ranking', name: 'app_user_ranking')]
    public function index(): Response
    {
        return $this->render('user_ranking/index.html.twig', [
            'controller_name' => 'UserRankingController',
        ]);
    }
}
