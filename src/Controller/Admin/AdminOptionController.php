<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\OptionType;
use App\Repository\OptionRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminOptionController extends AbstractController
{
    #[Route('/admin/option/{id}', name: 'detail_admin_option')]
    #[IsGranted('ROLE_ADMIN')]
    public function detail(int $id, OptionRepository $optionRepository): Response
    {
        $option = $optionRepository->find($id);
        if (!$option) {
            throw $this->createNotFoundException('Option not found');
        }

        return $this->render('admin_templates/admin_option/detail-option.html.twig', [
            'option' => $option,
        ]);
    }

    #[Route('/admin/add_option/{id}', name: 'create_admin_option')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(int $id, Request $request, QuestionRepository $questionRepository, EntityManagerInterface $em): Response
    {
        $option = new Option();
        $question = $questionRepository->find($id);
        $option->setQuestion($question);

        if (!$question) {
            throw $this->createNotFoundException('Question not found');
        }
        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $option->setCreatedAt(new \DateTimeImmutable());
            $option->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($option);
            $em->flush();

            $this->addFlash('success', 'Option created successfully.');

            return $this->redirectToRoute('detail_admin_question', ['id' => $question->getId()]);
        }

        return $this->render('admin_templates/admin_option/admin_create_option.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/edit_option/{id}', name: 'edit_admin_option')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(int $id, Request $request, OptionRepository $optionRepository, EntityManagerInterface $em): Response
    {
        $option = $optionRepository->find($id);
        if (!$option) {
            throw $this->createNotFoundException('Option not found');
        }

        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $option->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();

            $this->addFlash('success', 'Option updated successfully.');

            return $this->redirectToRoute('detail_admin_option', ['id' => $option->getId()]);
        }

        return $this->render('admin_templates/admin_option/admin_edit_option.html.twig', [
            'form' => $form->createView(),
            'option' => $option,
        ]);
    }

    #[Route('/admin/delete_option/{id}', name: 'delete_admin_option')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(int $id, OptionRepository $optionRepository, EntityManagerInterface $em): Response
    {
        $option = $optionRepository->find($id);
        if (!$option) {
            throw $this->createNotFoundException('The option does not exist');
        }

        $em->remove($option);
        $em->flush();

        return $this->redirectToRoute('detail_admin_question', ['id' => $option->getQuestion()->getId()]);
    }
}
