<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserLanguageCourseController extends AbstractController
{
    #[Route('/user/language/course', name: 'app_user_language_course')]
    public function index(): Response
    {
        return $this->render('user_language_course/index.html.twig', [
            'controller_name' => 'UserLanguageCourseController',
        ]);
    }
}
