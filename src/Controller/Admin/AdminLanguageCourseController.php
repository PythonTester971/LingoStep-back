<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminLanguageCourseController extends AbstractController
{
    #[Route('/admin/language_course', name: 'app_admin_language_course')]
    public function index(): Response
    {
        return $this->render('admin_templates/admin_language_course/index.html.twig', [
            'controller_name' => 'AdminLanguageCourseController',
        ]);
    }
}
