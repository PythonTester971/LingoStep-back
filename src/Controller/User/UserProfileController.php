<?php

namespace App\Controller\User;


use App\Entity\User;
use App\Form\UserType;
use App\Entity\UserLanguageCourse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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

            $selectedCourses = $form->get('languageCourses')->getData();

            // Clear existing UserLanguageCourse entries
            foreach ($user->getUserLanguageCourses() as $existingCourse) {
                $user->removeUserLanguageCourse($existingCourse);
            }

            foreach ($selectedCourses as $course) {
                $userLanguageCourse = new UserLanguageCourse();
                $userLanguageCourse->setUser($user);
                $userLanguageCourse->setLanguageCourse($course);
                $userLanguageCourse->setProgress(0);
                $user->addUserLanguageCourse($userLanguageCourse);
            }

            $user->setUpdatedAt(new \DateTimeImmutable());

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
        }

        return $this->render('user_templates/user_profile/edit-profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/delete/', name: 'app_user_profile_delete')]
    public function deleteProfile(EntityManagerInterface $em, Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Store user ID for flash message
        $userId = $user->getId();
        $username = $user->getUsername();

        // First, invalidate the session before we delete the user
        // This prevents Symfony from trying to refresh a deleted user
        $request->getSession()->invalidate();
        $this->container->get('security.token_storage')->setToken(null);

        // First, handle all AnsweredQuestions
        foreach ($user->getAnsweredQuestions() as $answer) {
            // Set Option and Question to null to avoid constraint issues
            $answer->setOptione(null);
            $answer->setQuestion(null);
            $em->persist($answer);
        }
        $em->flush();

        // Clear all UserLanguageCourses
        foreach ($user->getUserLanguageCourses() as $userLanguageCourse) {
            // This will cascade to UserMissions
            $user->removeUserLanguageCourse($userLanguageCourse);
        }

        // Clear all UserMissions
        foreach ($user->getUserMissions() as $userMission) {
            $user->removeUserMission($userMission);
        }

        $em->flush();

        // Now remove the user
        $em->remove($user);
        $em->flush();

        // Add a flash message
        $this->addFlash('success', "User account '{$username}' (ID: {$userId}) has been successfully deleted.");

        // Redirect to home page since we're already logged out
        return $this->redirectToRoute('home');
    }
}
