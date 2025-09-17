<?php

namespace App\Controller\Admin;

use App\Entity\LanguageCourse;
use App\Form\LanguageCourseType;
use App\Repository\LanguageCourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminLanguageCourseController extends AbstractController
{
    #[Route('/admin/language_course', name: 'app_admin_language_course')]
    public function index(LanguageCourseRepository $repository): Response
    {
        $languageCourses = $repository->findAll();

        return $this->render('admin_templates/admin_language_course/index.html.twig', [
            'languageCourses' => $languageCourses,
        ]);
    }

    #[Route('/admin/language_course/create', name: 'app_admin_language_course_create')]
    public function create(Request $request, LanguageCourseRepository $repository): Response
    {
        $languageCourse = new LanguageCourse();
        $form = $this->createForm(LanguageCourseType::class, $languageCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($languageCourse, true);

            return $this->redirectToRoute('app_admin_language_course');
        }

        return $this->render('admin_templates/admin_language_course/admin_create_language_course.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/language_course/edit/{id}', name: 'app_admin_language_course_edit')]
    public function edit(Request $request, LanguageCourse $languageCourse, LanguageCourseRepository $repository): Response
    {
        $form = $this->createForm(LanguageCourseType::class, $languageCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($languageCourse, true);

            return $this->redirectToRoute('app_admin_language_course');
        }

        return $this->render('admin_templates/admin_language_course/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/language_course/delete/{id}', name: 'app_admin_language_course_delete')]
    public function delete(LanguageCourse $languageCourse, LanguageCourseRepository $repository): RedirectResponse
    {
        $repository->remove($languageCourse, true);

        return $this->redirectToRoute('app_admin_language_course');
    }
}
