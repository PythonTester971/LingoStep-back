<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserOtherController extends AbstractController
{
    #[Route('/user/other', name: 'app_user_other')]
    public function index(): Response
    {
        return $this->render('user_other/index.html.twig', [
            'controller_name' => 'UserOtherController',
        ]);
    }
}
