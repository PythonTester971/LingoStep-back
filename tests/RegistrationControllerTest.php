<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Ensure we have a clean database
        $container = static::getContainer();

        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        $this->userRepository = $container->get(UserRepository::class);

        foreach ($this->userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();
    }

    public function testRegister(): void
    {
        // Register a new user
        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Sign up');

        $this->client->submitForm('Register', [
            'registration_form[username]' => 'Geoline',
            'registration_form[language]' => 1,
            'registration_form[email]' => 'geoline.denisse@wanadoo.fr',
            'registration_form[plainPassword]' => 'Password1234$',
            'registration_form[agreeTerms]' => true,
        ]);

        // Ensure the response redirects after submitting the form, the user exists, and is not verified
        //@TODO: set the appropriate path that the user is redirected to.
        self::assertResponseRedirects('/');
        self::assertCount(1, $this->userRepository->findAll());
    }
}
