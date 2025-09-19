<?php

namespace App\Controller\User;

use App\Repository\MissionRepository;
use App\Repository\LanguageCourseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserLanguageCourseController extends AbstractController
{
  #[Route('/user/courses', name: 'app_user_language_courses')]
  public function index(LanguageCourseRepository $languageCourseRepository, MissionRepository $missionRepository): Response
  {
    /** @var \App\Entity\User $user */
    $user = $this->getUser();
    if (!$user) {
      return $this->redirectToRoute('app_login');
    }

    $languageCourses = $languageCourseRepository->findBy(['language' => $user->getLanguage()]);

    return $this->render('user_templates/user_language_course/index.html.twig', [
      'languageCourses' => $languageCourses,
      'missions' => $missionRepository->findAll(),
    ]);
  }
}
