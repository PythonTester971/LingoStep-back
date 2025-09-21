<?php

namespace App\Controller\Admin;

use Dom\Entity;
use App\Entity\Mission;
use App\Entity\Question;
use App\Form\MissionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminMissionController extends AbstractController
{
    #[Route('/admin/mission', name: 'app_admin_mission')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin_templates/admin_mission/index.html.twig', [
            'controller_name' => 'AdminMissionController',
        ]);
    }

    #[Route('/admin/mission/create', name: 'app_admin_mission_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $mission = new Mission();

        $question1 = new Question();
        $question1->setInstruction('question1');
        $mission->getQuestions()->add($question1);

        $form = $this->createForm(MissionType::class, $mission);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mission->setCreatedAt(new \DateTimeImmutable());
            $mission->setUpdatedAt(new \DateTimeImmutable());

            foreach ($mission->getQuestions() as $question) {
                $question->setMission($mission);
                $em->persist($question);
            }

            $em->persist($mission);
            $em->flush();

            return $this->redirectToRoute('app_admin_mission');
        }

        return $this->render('admin_templates/admin_mission/create.html.twig', [
            'form' => $form,
        ]);
    }
}
