<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\LogRepository;
use AppBundle\Form\Dto\DateTimeInterval;
use AppBundle\Form\Dto\LogSearch;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\User;

class LogRepositoryTest extends KernelTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        if (null !== static::$kernel) {
            self::bootKernel([
                'environment' => 'test',
                'debug' => true,
            ]);
            $this->container = static::$kernel->getContainer();
        }
    }

    /**
     * @dataProvider getSearchData
     * @param array $logSearchData
     * @param string $currentLoginUser
     * @param int $resultedLogCount
     */
    public function testGetLogsSearchQuery(array $logSearchData, $currentLoginUser, $resultedLogCount)
    {
        $this->logInAs($currentLoginUser);
        $logSearch = $this->getLogSearchDto($logSearchData);

        $this->assertCount(
            $resultedLogCount,
            $this->container->get('app.repository.log')->getLogsSearchQuery($logSearch)->getArrayResult()
        );
    }

    public function testGetUsers()
    {
        $this->logInAs('admin');
        $this->assertCount(3, $this->container->get('app.repository.log')->getUsers());
    }

    public function testGetLastStamp()
    {
        $file = realpath(__DIR__."/../../DataFixtures/data/root-access_log");
        $date = new \DateTime('23/Nov/2015:02:46:43 +0200');
        $this->assertEquals($date->getTimestamp(), $this->container->get('app.repository.log')->getLastStamp($file));
    }

    public function getSearchData()
    {
        return [
            // [LogSearch:search, LogSearch:isRegExp, LogSerach:files, LogSerach:TimeIntervals, LogSerach:Users], CurrentLoginUser, ResultedLogCount
            [['', false, [], [], []], 'admin', 1118],
            [['', false, [], [], []], 'user1', 41],
            [['', false, [], [], []], 'user2', 38],
            [['', false, [realpath(__DIR__."/../../DataFixtures/data/root-access_log")], [], []], 'admin', 1039],
            [['', false, [realpath(__DIR__."/../../DataFixtures/data/root-access_log")], [], []], 'user1', 0],
            [['', false, [realpath(__DIR__."/../../DataFixtures/data/u1-d1.log"), realpath(__DIR__."/../../DataFixtures/data/u1-d2.log")], [], []], 'user1', 41],
            [['', false, [], [], ['user1']], 'admin', 41],
            [['', false, [], [], ['user2']], 'admin', 38],
            [['', false, [], [], ['user1', 'user2']], 'admin', 79],
            [['POST', false, [], [], []], 'user1', 2],
            [['_wdt/\D?', true, [], [], []], 'user1', 7],
//            [['', false, [], [], []], 'admin', 1118],
//            [['', false, [], [], []], 'admin', 1118],
//            [['', false, [], [], []], 'admin', 1118],
//            [['', false, [], [], []], 'admin', 1118],
//            [['', false, [], [], []], 'admin', 1118],
//            [['', false, [], [], []], 'admin', 1118],
        ];
    }

    /**
     * Clean up Kernel usage in this test.
     */
    public static function tearDownAfterClass()
    {
        static::ensureKernelShutdown();
    }

    private function logInAs($user)
    {
        $user = new User($user, 111);
        $token = new UsernamePasswordToken($user, 111, 'main');
        $this->container->get('security.token_storage')->setToken($token);
    }

    /**
     * @param array $data
     * @return LogSearch
     */
    private function getLogSearchDto(array $data)
    {
        $logSearch = new LogSearch();

        $logSearch->search = $data[0];
        $logSearch->isRegExp = $data[1];
        $logSearch->files = $data[2];

        foreach ($data[3] as $timeIntervalData) {
            $timeInterval = new DateTimeInterval();

            $timeInterval->from = $timeIntervalData[0];
            $timeInterval->to   = $timeIntervalData[1];

            $logSearch->timeIntervals[] = $timeInterval;
        }

        $logSearch->users = $data[4];

        return $logSearch;
    }
}
