<?php

namespace App\Controller\Admin;

use Dom\Entity;
use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LanguageCourseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminMissionController extends AbstractController
{
    #[Route('/admin/mission/{id}', name: 'detail_admin_mission')]
    #[IsGranted('ROLE_ADMIN')]
    public function detail(int $id, MissionRepository $missionRepository): Response
    {
        $mission = $missionRepository->find($id);
        $questions = $mission->getQuestions();
        if ($questions->isEmpty()) {
            $this->addFlash('warning', 'This mission has no questions.');
        }

        if (!$mission) {
            throw $this->createNotFoundException('Mission not found');
        }

        return $this->render('admin_templates/admin_mission/detail-mission.html.twig', [
            'mission' => $mission,
            'questions' => $questions,
        ]);
    }

    #[Route('/admin/add_quiz/{id}', name: 'create_admin_mission')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(int $id, Request $request, LanguageCourseRepository $languageCourseRepository, EntityManagerInterface $em): Response
    {
        $mission = new Mission();
        $languageCourse = $languageCourseRepository->find($id);
        $mission->setLanguageCourse($languageCourse);

        if (!$languageCourse) {
            throw $this->createNotFoundException('Language course not found');
        }
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mission->setCreatedAt(new \DateTimeImmutable());
            $mission->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($mission);
            $em->flush();

            $this->addFlash('success', 'Mission created successfully.');

            return $this->redirectToRoute('admin_language_course', ['id' => $languageCourse->getId()]);
        }

        return $this->render('admin_templates/admin_mission/create-mission.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/admin/edit_mission/{id}', name: 'edit_admin_mission')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(int $id, Request $request, MissionRepository $missionRepository, EntityManagerInterface $em): Response
    {
        $mission = $missionRepository->find($id);
        if (!$mission) {
            throw $this->createNotFoundException('Mission not found');
        }
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mission->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Mission updated successfully.');

            return $this->redirectToRoute('admin_language_course', ['id' => $mission->getLanguageCourse()->getId()]);
        }
        return $this->render('admin_templates/admin_mission/edit-mission.html.twig', [
            'form' => $form->createView(),
            'mission' => $mission,
        ]);
    }

    #[Route('/admin/delete_mission/{id}', name: 'delete_admin_mission')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id, MissionRepository $missionRepository, EntityManagerInterface $em): Response
    {
        $mission = $missionRepository->find($id);
        if (!$mission) {
            throw $this->createNotFoundException('The mission does not exist');
        }

        $em->remove($mission);
        $em->flush();

        return $this->redirectToRoute('admin_language_course', ['id' => $mission->getLanguageCourse()->getId()]);
    }
}
