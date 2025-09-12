<?php

namespace App\Controller\User;

use App\Repository\MissionRepository;
use App\Repository\LanguageCourseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserLanguageCourseController extends AbstractController
{
    #[Route('/languages_course/{id}/missions', name: 'language_course_missions')]
    public function missions(int $id, LanguageCourseRepository $languageCourseRepository): Response
    {

        $languageCourse = $languageCourseRepository->find($id);

        $missions = $languageCourse->getMissions();

        return $this->render('mission_list/index.html.twig', [
            'languageCourse' => $languageCourse,
            'missions' => $missions,
        ]);
    }
}
