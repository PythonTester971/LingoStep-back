<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use App\Entity\Question;
use App\Form\MissionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminQuizController extends AbstractController
{
    #[Route('/admin/quiz', name: 'app_admin_quiz')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin_templates/admin_quiz/index.html.twig', [
            'controller_name' => 'AdminQuizController',
        ]);
    }

    #[Route('/admin/quiz/create', name: 'app_admin_quiz_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em): Response
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

            foreach ($mission->getQuestions() as $question) {
                $question->setMission($mission);
                $em->persist($question);

                foreach ($question->getOptions() as $option) {
                    $option->setQuestion($question);
                    $em->persist($option);
                }
            }

            $em->persist($mission);
            $em->flush();

            return $this->redirectToRoute('app_admin_quiz');
        }

        return $this->render('admin_templates/admin_quiz/create.html.twig', [
            'form' => $form,
        ]);
    }
}
