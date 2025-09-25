<?php

namespace App\Controller\User;

use App\Entity\AnsweredQuestion;
use App\Repository\MissionRepository;
use App\Repository\AnsweredQuestionRepository;
use App\Repository\UserMissionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserAnsweredQuestionsController extends AbstractController
{
    #[Route('/user/answered_questions', name: 'user_answered_questions')]
    public function index(MissionRepository $missionRepository, UserMissionRepository $userMissionRepository, AnsweredQuestionRepository $answeredQuestionRepository): Response
    {
        $userAnsweredQuestions = $answeredQuestionRepository->findBy(['user' => $this->getUser()]);
        // $userMissions = $missionRepository->findAllMissionByUser($this->getUser());
        $userMissions = $userMissionRepository->findBy(['user' => $this->getUser()]);

        return $this->render('user_templates/user_answered_questions/index.html.twig', [
            'userAnsweredQuestions' => $userAnsweredQuestions,
            'userMissions' => $userMissions,
        ]);
    }
}
