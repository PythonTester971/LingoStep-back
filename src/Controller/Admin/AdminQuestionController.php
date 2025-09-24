<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\MissionRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminQuestionController extends AbstractController
{
    #[Route('/admin/question/{id}', name: 'detail_admin_question')]
    #[IsGranted('ROLE_ADMIN')]
    public function detail(int $id, QuestionRepository $questionRepository): Response
    {
        $question = $questionRepository->find($id);
        if (!$question) {
            throw $this->createNotFoundException('Question not found');
        }
        $options = $question->getOptions();
        if (!$options) {
            throw $this->createNotFoundException('Options not found for this question');
        }

        return $this->render('admin_templates/admin_question/detail-question.html.twig', [
            'question' => $question,
            'options' => $options,
        ]);
    }

    #[Route('/admin/add_question/{id}', name: 'create_admin_question')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(int $id, Request $request, MissionRepository $missionRepository, EntityManagerInterface $em): Response
    {
        $question = new Question();
        $mission = $missionRepository->find($id);
        $question->setMission($mission);

        if (!$mission) {
            throw $this->createNotFoundException('Mission not found');
        }
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $question->setCreatedAt(new \DateTimeImmutable());
            $question->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'Question created successfully.');

            return $this->redirectToRoute('detail_admin_mission', ['id' => $mission->getId()]);
        }

        return $this->render('admin_templates/admin_question/create-question.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
