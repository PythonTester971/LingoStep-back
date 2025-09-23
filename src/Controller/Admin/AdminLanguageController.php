<?php

namespace App\Controller\Admin;

use App\Entity\Language;
use App\Form\LanguageType;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminLanguageController extends AbstractController
{
    #[Route('/admin/languages', name: 'admin_languages')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(LanguageRepository $languageRepository): Response
    {
        $languages = $languageRepository->findAll();

        return $this->render('admin_templates/admin_language/index.html.twig', [
            'languages' => $languages,
        ]);
    }

    #[Route('/admin/language/create', name: 'app_admin_language_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $language = new Language();
        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $language->setCreatedAt(new \DateTimeImmutable());
            $language->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($language);
            $em->flush();

            return $this->redirectToRoute('admin_languages');
        }

        return $this->render('admin_templates/admin_language/language_create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/language/{id}', name: 'admin_language_detail', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function detail(LanguageRepository $languageRepository, int $id): Response
    {
        $language = $languageRepository->find($id);

        if (!$language) {
            throw $this->createNotFoundException('The language does not exist');
        }

        return $this->render('admin_templates/admin_language/language_detail.html.twig', [
            'language' => $language,
        ]);
    }

    #[Route('/admin/language/{id}/delete', name: 'admin_language_delete', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $em, LanguageRepository $languageRepository, int $id): Response
    {
        $language = $languageRepository->find($id);

        if (!$language) {
            throw $this->createNotFoundException('The language does not exist');
        }

        $em->remove($language);
        $em->flush();

        return $this->redirectToRoute('admin_languages');
    }

    #[Route('/admin/language/{id}/edit', name: 'admin_language_edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, EntityManagerInterface $em, LanguageRepository $languageRepository, int $id): Response
    {
        $language = $languageRepository->find($id);

        if (!$language) {
            throw $this->createNotFoundException('The language does not exist');
        }

        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $language->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($language);
            $em->flush();



            return $this->redirectToRoute('admin_language_detail', ['id' => $language->getId()]);
        }

        return $this->render('admin_templates/admin_language/language_edit.html.twig', [
            'form' => $form->createView(),
            'language' => $language,
        ]);
    }
}
