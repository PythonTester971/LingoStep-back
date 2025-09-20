<?php

namespace App\Controller\Admin;

use App\Entity\Mission;
use App\Entity\Question;
use App\Form\MissionType;
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
    public function create(Request $request): Response
    {
        $mission = new Mission();

        // dummy code - add some example questions to the mission
        // (otherwise, the template will render an empty list of questions)
        $question1 = new Question();
        $question1->setInstruction('question1');
        $mission->getQuestions()->add($question1);
        // end dummy code

        $form = $this->createForm(MissionType::class, $mission);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... do your form processing, like saving the Task and Tag entities
        }

        return $this->render('admin_templates/admin_mission/create.html.twig', [
            'form' => $form,
        ]);
    }
}
