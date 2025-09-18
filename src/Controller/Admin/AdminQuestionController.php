<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminQuestionController extends AbstractController
{
    #[Route('/admin/question', name: 'app_admin_question')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin_question/index.html.twig', [
            'controller_name' => 'AdminQuestionController',
        ]);
    }
}
