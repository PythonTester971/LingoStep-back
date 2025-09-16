<?php

namespace App\Controller;

use App\Repository\LanguageCourseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class LanguageCourseController extends AbstractController
{
    #[Route('/language-course/{id}', name: 'language_courses', methods: ['GET'])]
    public function course($id, LanguageCourseRepository $langCoursRepository): Response
    {
        $languageCourse = $langCoursRepository->findBy([], ['id' => 'ASC'], 4);

        return $this->render('language_course/index.html.twig', [
            'languageCourse' => $languageCourse,
        ]);
    }
}
