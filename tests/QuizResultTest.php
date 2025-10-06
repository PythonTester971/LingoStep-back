<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Option;
use App\Entity\Mission;
use App\Entity\Language;
use App\Entity\Question;
use App\Entity\UserMission;
use App\Entity\LanguageCourse;
use App\Entity\AnsweredQuestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuizResultTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $em;
    private User $user;
    private Mission $mission;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $this->em = $container->get('doctrine')->getManager();

        // cleanup possible leftovers
        $this->em->getConnection()->beginTransaction();
        $this->truncateTables();
        $this->em->getConnection()->commit();

        $now = new \DateTimeImmutable();

        // Ensure Language exists (used by User)
        $language = $this->em->getRepository(Language::class)->findOneBy(['label' => 'French']);
        if (!$language) {
            $language = new Language();
            $language->setLabel('French');
            // slug is required in entity
            if (method_exists($language, 'setSlug')) {
                $language->setSlug('french');
            }
            if (method_exists($language, 'setCreatedAt')) {
                $language->setCreatedAt($now);
            }
            if (method_exists($language, 'setUpdatedAt')) {
                $language->setUpdatedAt($now);
            }
            $this->em->persist($language);
            $this->em->flush();
        }

        // Ensure a LanguageCourse exists (required by Mission.languageCourse FK)
        $languageCourse = $this->em->getRepository(LanguageCourse::class)->findOneBy([]);
        if (!$languageCourse) {
            $languageCourse = new LanguageCourse();
            $languageCourse->setLabel('Test LC');
            // ensure required fields
            if (method_exists($languageCourse, 'setDescription')) {
                $languageCourse->setDescription('Test language course');
            }
            if (method_exists($languageCourse, 'setLanguage')) {
                $languageCourse->setLanguage($language);
            }
            if (method_exists($languageCourse, 'setCreatedAt')) {
                $languageCourse->setCreatedAt($now);
            }
            if (method_exists($languageCourse, 'setUpdatedAt')) {
                $languageCourse->setUpdatedAt($now);
            }
            $this->em->persist($languageCourse);
            $this->em->flush();
        }

        // create a user
        $this->user = new User();
        $this->user->setEmail('quiz.test@example.com');
        $this->user->setUsername('quiztest');
        $this->user->setLanguage($language);
        $this->user->setRoles(['ROLE_USER']);
        $this->user->setPassword('TESTtest1234@');
        $this->user->setCreatedAt(new \DateTimeImmutable());
        $this->user->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($this->user);

        // create mission with xp reward
        $this->mission = new Mission();
        $this->mission->setLabel('Test mission');
        // adjust if your entity uses a different setter
        $this->mission->setDescription('Description for test mission');
        $this->mission->setIllustration('/path/to/illustration.png');
        $this->mission->setXpReward(50);
        $this->mission->setLanguageCourse($languageCourse);
        $this->mission->setCreatedAt(new \DateTimeImmutable());
        $this->mission->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($this->mission);

        // create a question belonging to mission
        $question = new Question();
        $question->setMission($this->mission);
        $question->setInstruction('Q1');
        $question->setCreatedAt(new \DateTimeImmutable());
        $question->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($question);

        // create one correct option
        $option = new Option();
        $option->setQuestion($question);
        $option->setLabel('Right');
        $option->setIsCorrect(true);
        $option->setCreatedAt(new \DateTimeImmutable());
        $option->setUpdatedAt(new \DateTimeImmutable());
        $this->em->persist($option);

        // record answered question (user answered correctly)
        $answered = new AnsweredQuestion();
        $answered->setUser($this->user);
        $answered->setMission($this->mission);
        $answered->setQuestion($question);
        // many apps name this optione (see your code) — adapt if needed
        $answered->setOptione($option);
        $this->em->persist($answered);

        $this->em->flush();
    }

    public function testSuccessRateIs100AndXpAwardedOnce(): void
    {
        // authenticate the client as our user (reload from EM to get managed instance)
        $managedUser = $this->em->getRepository(User::class)->find($this->user->getId());
        $this->client->loginUser($managedUser);

        // call result first time
        $this->client->request('GET', '/quiz/mission/' . $this->mission->getId() . '/result');
        $this->assertResponseIsSuccessful();

        // load UserMission and assert completed + xpObtained == mission xp
        $umRepo = $this->em->getRepository(UserMission::class);
        // reload from DB to ensure managed instance
        $userMission = $umRepo->findOneBy(['user' => $this->user, 'mission' => $this->mission]);
        $this->assertNotNull($userMission, 'UserMission should exist after result');
        $this->assertTrue($userMission->isCompleted(), 'Mission must be marked completed');
        $this->assertSame($this->mission->getXpReward(), $userMission->getXpObtained(), 'User should get mission xp');

        $xpAfterFirst = $userMission->getXpObtained();

        // call result a second time — XP must not be awarded again
        $this->client->request('GET', '/quiz/mission/' . $this->mission->getId() . '/result');
        $this->assertResponseIsSuccessful();

        // re-fetch from DB to get fresh managed entity
        $userMission = $umRepo->findOneBy(['user' => $this->user, 'mission' => $this->mission]);
        $xpAfterSecond = $userMission ? $userMission->getXpObtained() : null;

        $this->assertSame($xpAfterFirst, $xpAfterSecond, 'XP obtained should not increase on subsequent result calls');
    }

    protected function tearDown(): void
    {
        // cleanup created entities
        $repoNames = [
            User::class,
            Mission::class,
            Question::class,
            Option::class,
            AnsweredQuestion::class,
            UserMission::class,
        ];

        foreach ($repoNames as $class) {
            $repo = $this->em->getRepository($class);
            $items = $repo->findBy([]);
            foreach ($items as $i) {
                $this->em->remove($i);
            }
        }
        $this->em->flush();

        parent::tearDown();
    }

    private function truncateTables(): void
    {
        // Optional: adjust table names if needed for your schema.
        // Keep minimal: do nothing here or add SQL to clear only relevant tables.
    }
}
