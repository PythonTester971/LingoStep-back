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
}
