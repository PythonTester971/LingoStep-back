<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile')]
    public function userDetail(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user_templates/user_profile/index.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/profile/edit/{user_id}', name: 'app_user_profile_edit')]
    public function editProfile(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user_templates/user_profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
