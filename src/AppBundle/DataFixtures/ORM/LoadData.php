<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Parser\LogParser;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData implements FixtureInterface
{
    protected $usersFiles = [
        __DIR__.'/../data/u1-d1.log' => 'user1',
        __DIR__.'/../data/u1-d2.log' => 'user1',
        __DIR__.'/../data/u2-d1.log' => 'user2',
        __DIR__.'/../data/u2-d2.log' => 'user2',
        __DIR__.'/../data/root-access_log' => 'admin',
    ];
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $parser = new LogParser();
        $batchSize = 20;

        foreach ($this->usersFiles as $file => $user) {
            $logs = $parser->parseFile(realpath($file), $user);

            foreach ($logs as $key => $log) {
                $manager->persist($log);

                if (($key % $batchSize) === 0) {
                    $manager->flush();
                    $manager->clear();
                }
            }

            $manager->flush();
            $manager->clear();
        }
    }
}
