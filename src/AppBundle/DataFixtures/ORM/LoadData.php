<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Parser\LogParser;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadData implements FixtureInterface, ContainerAwareInterface
{
    protected $usersFiles = [
        __DIR__."/../data/u1-d1.log"       => 'user1',
        __DIR__."/../data/u1-d2.log"       => "user1",
        __DIR__."/../data/u2-d1.log"       => "user2",
        __DIR__."/../data/u2-d2.log"       => "user2",
        __DIR__."/../data/root-access_log" => "admin"
    ];

    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $parser = new LogParser();

        foreach ($this->usersFiles as $file => $user) {
            $logs = $parser->parseFile(realpath($file), $user);
            array_map(function($log) use ($em) {
                $em->persist($log);
            }, $logs);
        }

        $em->flush();
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
