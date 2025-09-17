<?php

namespace App\Controller\User;


use App\Form\UserType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile')]
    public function userDetail(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user_templates/user_profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/edit/', name: 'app_user_profile_edit')]
    public function editProfile(Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // // Clear existing language courses
            // foreach ($user->getLanguageCourses() as $languageCourse) {
            //     $user->removeLanguageCourse($languageCourse);
            // }

            // Add new language courses
            foreach ($form->get('languageCourses')->getData() as $languageCourse) {
                $user->addLanguageCourse($languageCourse);
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
        }

        return $this->render('user_templates/user_profile/edit-profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
