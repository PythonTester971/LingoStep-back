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
use App\Repository\UserMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class QuizController extends AbstractController
{
    #[Route('/quiz/mission/{mission_id}/question/{question_id}', name: 'app_quiz')]
    public function createQuiz(
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

        $questions = $questionRepository->findBy(['mission' => $mission], ['id' => 'ASC']);

        $currentIndex = array_search($question, $questions, true);

        $nextQuestion = $questions[$currentIndex + 1] ?? null;

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
                'form' => $form->createView(),
                'question' => $question,
                'mission' => $mission,
            ]);
        }

        return $this->render('quiz/index.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
            'mission' => $mission,
            'index' => $currentIndex + 1,
        ]);
    }


    #[Route('/quiz/mission/{mission_id}/result', name: 'app_quiz_result')]
    public function showResult(
        int                          $mission_id,
        MissionRepository            $missionRepository,
        UserMissionRepository        $userMissionRepository,
        EntityManagerInterface       $em
    ): Response {
        $mission = $missionRepository->find($mission_id);

        /** @var \App\Entity\User $tokenUser */
        $tokenUser = $this->getUser();

        $user = $em->getRepository(\App\Entity\User::class)->find($tokenUser->getId());
        if (!$user) {
            throw new \RuntimeException('User not found');
        }

        $allAnswered = $em->getRepository(AnsweredQuestion::class)->findBy([
            'mission' => $mission,
        ]);

        $answered = array_filter($allAnswered, function ($ans) use ($user) {
            return $ans->getUser() && $ans->getUser()->getId() === $user->getId();
        });
        $score = 0;
        foreach ($answered as $ans) {
            $option = $ans->getOptione();
            if ($option && $option->isCorrect()) {
                $score++;
            }
        }
        $total = count($answered);
        $successRate = $total > 0 ? ($score / $total) * 100 : 0;

        $userMission = null;
        $userMissions = $userMissionRepository->findBy(['mission' => $mission]);
        foreach ($userMissions as $uM) {
            if ($uM->getUser() && $uM->getUser()->getId() === $user->getId()) {
                $userMission = $uM;
                break;
            }
        }

        if (!$userMission) {
            $userMission = new UserMission();
            $userMission->setUser($user);
            $userMission->setMission($mission);
        }

        if ($successRate >= 70) {
            // Award XP if the mission wasn't completed yet OR xp wasn't recorded (xpObtained == 0)
            $xpNotRecorded = ((int) $userMission->getXpObtained() === 0);
            if (!$userMission->isCompleted() || $xpNotRecorded) {
                $userMission->setXpObtained($mission->getXpReward());
                $userMission->setIsCompleted(true);
                $userMission->setCompletedAt(new \DateTimeImmutable());

                $currentXp = (int) $user->getXp();
                $user->setXp($currentXp + (int) $userMission->getXpObtained());
                $em->persist($user);
            }
        } else {

            if (!$userMission->isCompleted()) {
                $userMission->setXpObtained(0);
                $userMission->setIsCompleted(false);
                $userMission->setCompletedAt(null);
            }
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
