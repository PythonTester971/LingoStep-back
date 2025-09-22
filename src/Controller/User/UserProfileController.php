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
    public function deleteProfile(EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('home');
    }
}
