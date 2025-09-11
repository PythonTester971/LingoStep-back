<?php

namespace App\Controller;

use App\Repository\LanguageRepository;
use App\Repository\LanguageCourseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class LanguageCourseController extends AbstractController
{
    #[Route('/language-course-all', name: 'language_courses_list', methods: ['GET'])]
    public function list(LanguageRepository $languageRepository, LanguageCourseRepository $langCoursRepository): Response
    {
        $languages = $languageRepository->findAll();
        $languageCourses = $langCoursRepository->findAll();

        return $this->render('language_course/index.html.twig', [
            'languageCourses' => $languageCourses,
            'languages' => $languages,
        ]);
    }
}
