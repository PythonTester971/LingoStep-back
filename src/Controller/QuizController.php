<?php

namespace App\Controller;

use App\Form\QuizType;
use App\Entity\AnsweredQuestion;
use App\Repository\MissionRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class QuizController extends AbstractController
{
    #[Route('/quiz/mission/{mission_id}/question/{question_id}', name: 'app_quiz')]
    public function index($mission_id, $question_id, MissionRepository $missionRepository, QuestionRepository $questionRepository, Request $request, EntityManagerInterface $em): Response
    {
        $mission = $missionRepository->find($mission_id);
        $question = $questionRepository->find($question_id);

        $form = $this->createForm(QuizType::class, null, [
            'question' => $question,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $answeredQuestion = new AnsweredQuestion();
            $answeredQuestion->setUser($this->getUser());
            $answeredQuestion->setMission($mission);
            $answeredQuestion->setQuestion($question);
            $answeredQuestion->setOptione($data['question']);

            $em->persist($answeredQuestion);
            $em->flush();
        }


        return $this->render('quiz/index.html.twig', [
            'form' => $form->createView(),
            'mission' => $mission,
            'question' => $question,
        ]);
    }
}
