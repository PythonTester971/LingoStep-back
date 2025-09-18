<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminAnsweredQuestionController extends AbstractController
{
    #[Route('/admin/answered/question', name: 'app_admin_answered_question')]
    public function index(): Response
    {
        return $this->render('admin_answered_question/index.html.twig', [
            'controller_name' => 'AdminAnsweredQuestionController',
        ]);
    }
}
