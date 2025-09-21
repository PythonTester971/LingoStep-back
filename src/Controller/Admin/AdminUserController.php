<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class AdminUserController extends AbstractController
{
    #[Route('/admin/users', name: 'app_admin_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            dump($user->getUsername());
            dump($user->getEmail());
        }

        return $this->render('admin_templates/admin_user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/{id}', name: 'app_admin_user')]
    #[IsGranted('ROLE_ADMIN')]
    public function detail(UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('admin_templates/admin_user/user_detail.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/admin/user/create', name: 'app_admin_user_create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setUpdatedAt(new \DateTimeImmutable());
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));
            $user->setPicturePath('default-avatar.png');
            $user->setXp(0);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin_templates/admin_user/admin_create_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/user/edit/{id}', name: 'app_admin_user_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('admin_templates/admin_user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/user/delete/{id}', name: 'app_admin_user_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(User $user, UserRepository $userRepository): RedirectResponse
    {
        $userRepository->remove($user, true);

        return $this->redirectToRoute('app_admin_user');
    }
}
