<?php
namespace App\Tests;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractTestCase extends WebTestCase
{
    /**
     * @var ORMExecutor
     */
    private $fixtureExecutor = null;
    /**
     * @var ContainerAwareLoader
     */
    private $fixtureLoader = null;
    protected $client;
    protected static $schemaUpdated = false;
    protected function setUp(): void
    {
        $this->client = self::createClient();
        if (!self::$schemaUpdated) {
            $this->primeDatabase($this->client->getKernel());
            self::$schemaUpdated = true;
        }
    }
    protected function tearDown(): void
    {
        $this->clearDatabase();
        parent::tearDown();
    }
    protected function getEntityManager(): EntityManagerInterface
    {
        return static::getContainer()->get(EntityManagerInterface::class);
    }
    protected function getParameterBag(): ParameterBagInterface
    {
        return static::getContainer()->get(ParameterBagInterface::class);
    }
    
    private function primeDatabase(KernelInterface $kernel): void
    {
        if ('test' !== $kernel->getEnvironment()) {
            throw new \LogicException('Primer must be executed in the test environment');
        }
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadatas);
    }

    private function clearDatabase(): void
    {
        $purger = new ORMPurger(self::getEntityManager());
        $purger->purge();
    }
    
    /**
     * Adds a new fixture to be loaded.
     */
    protected function addFixture(FixtureInterface $fixture)
    {
        $this->getFixtureLoader()->addFixture($fixture);
    }
    /**
     * Executes all the fixtures that have been loaded so far.
     */
    protected function executeFixtures(): void
    {
        $this->getFixtureExecutor()->execute($this->getFixtureLoader()->getFixtures());
    }
    /**
     * Get the class responsible for loading the data fixtures.
     */
    private function getFixtureExecutor(): ORMExecutor
    {
        if ($this->fixtureExecutor === null) {
            /** @var EntityManager $entityManager */
            $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
            $this->fixtureExecutor = new ORMExecutor($entityManager, new ORMPurger($entityManager));
        }
        return $this->fixtureExecutor;
    }
    /**
     * Get the Doctrine data fixtures loader.
     */
    private function getFixtureLoader(): ContainerAwareLoader
    {
        if ($this->fixtureLoader === null) {
            $this->fixtureLoader = new ContainerAwareLoader(static::$kernel->getContainer());
        }
        return $this->fixtureLoader;
    }
}