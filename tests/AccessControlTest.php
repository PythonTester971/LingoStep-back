<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Language;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class AccessControlTest extends WebTestCase
{
    private EntityManagerInterface $em;
    private User $user;
    private User $admin;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $this->em = $container->get('doctrine')->getManager();

        $repo = $this->em->getRepository(User::class);
        $existing = $repo->findOneBy(['email' => 'testuser@example.com']);
        if ($existing) {
            $this->em->remove($existing);
        }
        $existing = $repo->findOneBy(['email' => 'testadmin@example.com']);
        if ($existing) {
            $this->em->remove($existing);
        }
        $this->em->flush();

        $this->user = new User();
        $this->user->setUsername('testuser');
        $this->user->setEmail('testuser@example.com');
        $this->user->setLanguage($this->em->getRepository(Language::class)->findOneBy(['label' => 'French']));
        $this->user->setRoles(['ROLE_USER']);
        // Hash the password for the test user
        $passwordHasher = $container->get('security.password_hasher');
        $hashed = $passwordHasher->hashPassword($this->user, 'Testtest1234@');
        $this->user->setPassword($hashed);
        $this->user->setCreatedAt(new \DateTimeImmutable());
        $this->user->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($this->user);

        $this->admin = new User();
        $this->admin->setUsername('testadmin');
        $this->admin->setEmail('testadmin@example.com');
        $this->admin->setLanguage($this->em->getRepository(Language::class)->findOneBy(['label' => 'French']));
        $this->admin->setRoles(['ROLE_ADMIN']);
        $hashedAdmin = $passwordHasher->hashPassword($this->admin, 'Testtest1234@');
        $this->admin->setPassword($hashedAdmin);
        $this->admin->setCreatedAt(new \DateTimeImmutable());
        $this->admin->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($this->admin);

        $this->em->flush();
    }

    public function testUserCannotAccessAdminRoute(): void
    {
        $this->client->loginUser($this->user);
        $this->client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testAdminCanAccessAdminRoute(): void
    {
        $this->client->loginUser($this->admin);
        $this->client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(200);
    }

    protected function tearDown(): void
    {
        $repo = $this->em->getRepository(User::class);
        foreach (['testuser@example.com', 'testadmin@example.com'] as $email) {
            $u = $repo->findOneBy(['email' => $email]);
            if ($u) {
                $this->em->remove($u);
            }
        }
        $this->em->flush();

        parent::tearDown();
    }
}
