<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use App\Entity\Question;
use App\Form\MissionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\QuizFormProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminQuizController extends AbstractController
{
    #[Route('/admin/quiz', name: 'app_admin_quiz')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(EntityManagerInterface $em): Response
    {
        $missions = $em->getRepository(Mission::class)->findAll();

        return $this->render('admin_templates/admin_quiz/index.html.twig', [
            'missions' => $missions,
        ]);
    }

    #[Route('/admin/quiz/create', name: 'app_admin_quiz_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em, QuizFormProcessor $quizFormProcessor): Response
    {
        $mission = new Mission();

        $question1 = new Question();
        $question1->setInstruction('Enter your question here');

        $option1 = new \App\Entity\Option();
        $option1->setLabel('Answer option 1');
        $option1->setIsCorrect(true);
        $question1->addOption($option1);


        $mission->getQuestions()->add($question1);

        $form = $this->createForm(MissionType::class, $mission);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mission->setCreatedAt(new \DateTimeImmutable());
            $mission->setUpdatedAt(new \DateTimeImmutable());

            // Process the mission using our dedicated service
            $quizFormProcessor->processMission($mission);

            // Using try-catch to identify any issues during persistence
            try {
                $em->persist($mission);
                $em->flush();
                $this->addFlash('success', 'Quiz created successfully!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error creating quiz: ' . $e->getMessage());
            }

            return $this->redirectToRoute('app_admin_quiz');
        }

        return $this->render('admin_templates/admin_quiz/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/quiz/edit/{id}', name: 'app_admin_quiz_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Mission $mission, Request $request, EntityManagerInterface $em, QuizFormProcessor $quizFormProcessor): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mission->setUpdatedAt(new \DateTimeImmutable());

            // Process the mission using our dedicated service
            $quizFormProcessor->processMission($mission);            // Using try-catch to identify any issues during persistence
            try {
                $em->persist($mission);
                $em->flush();
                $this->addFlash('success', 'Quiz updated successfully!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error updating quiz: ' . $e->getMessage());
            }
            return $this->redirectToRoute('app_admin_quiz');
        }

        return $this->render('admin_templates/admin_quiz/edit.html.twig', [
            'form' => $form,
            'mission' => $mission
        ]);
    }

    #[Route('/admin/quiz/delete/{id}', name: 'app_admin_quiz_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Mission $mission, EntityManagerInterface $em): Response
    {
        try {
            // When we delete a mission, related UserMission and AnsweredQuestion records
            // will have their mission_id set to NULL thanks to onDelete="SET NULL"
            // This preserves the user XP while allowing the mission to be deleted
            $em->remove($mission);
            $em->flush();
            $this->addFlash('success', 'Quiz deleted successfully without affecting user XP!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error deleting quiz: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_admin_quiz');
    }
}
