<?php

namespace App\Controller;

use App\Form\QuizType;
use App\Entity\UserMission;
use App\Entity\AnsweredQuestion;
use App\Repository\MissionRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserLanguageCourseRepository;
use App\Repository\UserMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class QuizController extends AbstractController
{
    #[Route('/quiz/mission/{mission_id}/question/{question_id}', name: 'app_quiz')]
    public function index(
        $mission_id,
        $question_id,
        MissionRepository $missionRepository,
        QuestionRepository $questionRepository,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $mission = $missionRepository->find($mission_id);
        $question = $questionRepository->find($question_id);

        $form = $this->createForm(QuizType::class, null, [
            'question' => $question,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->getUser();

            $answeredQuestion = $em->getRepository(AnsweredQuestion::class)->findOneBy([
                'user' => $user,
                'question' => $question,
            ]);

            if (!$answeredQuestion) {
                $answeredQuestion = new AnsweredQuestion();
                $answeredQuestion->setUser($this->getUser());
                $answeredQuestion->setMission($mission);
                $answeredQuestion->setQuestion($question);
            }

            $answeredQuestion->setOptione($data['question']);

            $em->persist($answeredQuestion);
            $em->flush();

            $questions = $questionRepository->findBy(['mission' => $mission], ['id' => 'ASC']);

            $currentIndex = array_search($question, $questions, true);

            $nextQuestion = $questions[$currentIndex + 1] ?? null;


            if ($nextQuestion) {
                return $this->redirectToRoute('app_quiz', [
                    'mission_id' => $mission->getId(),
                    'question_id' => $nextQuestion->getId(),
                ]);
            } else {
                return $this->redirectToRoute('app_quiz_result', [
                    'mission_id' => $mission->getId(),
                ]);
            }
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Veuillez sélectionner une réponse.');

            return $this->render('quiz/index.html.twig', [
                'controller_name' => 'QuizController',
                'form' => $form->createView(),
                'question' => $question,
                'mission' => $mission,
            ]);
        }

        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'QuizController',
            'form' => $form->createView(),
            'question' => $question,
            'mission' => $mission,
        ]);
    }


    #[Route('/quiz/mission/{mission_id}/result', name: 'app_quiz_result')]
    public function result(
        int $mission_id,
        MissionRepository $missionRepository,
        UserLanguageCourseRepository $userLanguageCourseRepository,
        UserMissionRepository $userMissionRepository,
        EntityManagerInterface $em
    ): Response {
        $mission = $missionRepository->find($mission_id);

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $answered = $mission->getAnsweredQuestions($user);
        $score = 0;
        foreach ($answered as $ans) {
            if ($ans->getOptione()->isCorrect()) {
                $score++;
            }
        }
        $total = count($answered);
        $successRate = $total > 0 ? ($score / $total) * 100 : 0;

        $userMission = $userMissionRepository->findOneBy([
            'user' => $user,
            'mission' => $mission,
        ]);

        if (!$userMission) {
            $userMission = new UserMission();
            $userMission->setUser($user);
            $userMission->setMission($mission);
        }

        $userLanguageCourse = $userLanguageCourseRepository->findOneBy([
            'user' => $user,
            'languageCourse' => $mission->getLanguageCourse(),
        ]);

        if ($userLanguageCourse) {
            $userMission->setUserLanguageCourse($userLanguageCourse);
        }

        if ($successRate >= 70) {
            if (!$userMission->isCompleted()) {

                $userMission->setXpObtained($mission->getXpReward());
                $userMission->setIsCompleted(true);
                $userMission->setCompletedAt(new \DateTimeImmutable());
                $user->setXp($user->getXp() + $userMission->getXpObtained());
            }
        } else {

            $userMission->setXpObtained(0);
            $userMission->setIsCompleted(false);
            $userMission->setCompletedAt(null);
            $this->addFlash('warning', 'Mission échouée, vous n’avez gagné aucun XP.');
        }

        $em->persist($userMission);
        $em->flush();

        return $this->render('quiz/result.html.twig', [
            'mission' => $mission,
            'languageCourse' => $mission->getLanguageCourse(),
            'score' => $score,
            'total' => $total,
            'successRate' => $successRate,
            'xpObtained' => $userMission->getXpObtained(),
        ]);
    }
}
