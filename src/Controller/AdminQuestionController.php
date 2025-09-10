<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminQuestionController extends AbstractController
{
    #[Route('/admin/question', name: 'app_admin_question')]
    public function index(): Response
    {
        return $this->render('admin_question/index.html.twig', [
            'controller_name' => 'AdminQuestionController',
        ]);
    }
}
