<?php

namespace App\Controller\Admin;

use App\Entity\LanguageCourse;
use App\Form\LanguageCourseType;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LanguageCourseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminLanguageCourseController extends AbstractController
{
    #[Route('/admin/language_course', name: 'admin_language_course')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(LanguageCourseRepository $languageRepository): Response
    {
        $languageCourses = $languageRepository->findAll();

        return $this->render('admin_templates/admin_language_course/index.html.twig', [
            'languageCourses' => $languageCourses,
        ]);
    }

    #[Route('/admin/language_course/{id}', name: 'admin_language_course_detail', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function detail(int $id, LanguageCourseRepository $languageCourseRepository): Response
    {
        $languageCourse = $languageCourseRepository->find($id);

        return $this->render('admin_templates/admin_language_course/detail.html.twig', [
            'languageCourse' => $languageCourse,
        ]);
    }

    #[Route('/admin/language_course/create', name: 'app_admin_language_course_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $languageCourse = new LanguageCourse();
        $form = $this->createForm(LanguageCourseType::class, $languageCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $languageCourse->setCreatedAt(new \DateTimeImmutable());
            $languageCourse->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($languageCourse);
            $em->flush();

            return $this->redirectToRoute('admin_language_course');
        }

        return $this->render('admin_templates/admin_language_course/admin_create_language_course.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/language_course/edit/{id}', name: 'app_admin_language_course_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(int $id, Request $request, LanguageCourseRepository $languageCourseRepository, EntityManagerInterface $em): Response
    {
        $languageCourse = $languageCourseRepository->find($id);

        $form = $this->createForm(LanguageCourseType::class, $languageCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $languageCourse->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($languageCourse);
            $em->flush();

            return $this->redirectToRoute('admin_language_course');
        }

        return $this->render('admin_templates/admin_language_course/edit.html.twig', [
            'form' => $form->createView(),
            'languageCourse' => $languageCourse,
        ]);
    }

    #[Route('/admin/language_course/delete/{id}', name: 'app_admin_language_course_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id, LanguageCourse $languageCourse, LanguageCourseRepository $languageRepository, EntityManagerInterface $em): RedirectResponse
    {
        $languageCourse = $languageRepository->find($id);
        if (!$languageCourse) {
            throw $this->createNotFoundException('The language course does not exist');
        }

        $em->remove($languageCourse);
        $em->flush();

        return $this->redirectToRoute('admin_language_course');
    }
}
