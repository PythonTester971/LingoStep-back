<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserAnsweredQuestionsController extends AbstractController
{
    #[Route('/user/answered/questions', name: 'app_user_answered_questions')]
    public function index(): Response
    {
        return $this->render('user_answered_questions/index.html.twig', [
            'controller_name' => 'UserAnsweredQuestionsController',
        ]);
    }
}
