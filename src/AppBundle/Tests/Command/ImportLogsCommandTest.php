<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\ImportLogsCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Tester\CommandTester;

class ImportLogsCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testImporFiles()
    {
        $command = $this->getCommandMock();
        $container = $this->getContainerMock();
        $em = $this->getEmMock();
        $repository = $this->getLogRepositoryMock();

        $repository->method('getLastStamp')->willReturn(0);
        $em->method('getRepository')->willReturn($repository);
        $em->expects($this->exactly(1118))->method('persist');
        $container->method('get')->willReturn($em);

        $command->method('getUserLogDir')->willReturn(__DIR__.'/../../DataFixtures/data');
        $command->setContainer($container);
        $commandTester = new CommandTester($command);
        $commandTester->execute(['username' => 'root']);

        $this->assertRegExp('/Importing.*u2-d2.log/', $commandTester->getDisplay());
        $this->assertRegExp('/Importing.*root-access_log/', $commandTester->getDisplay());
        $this->assertRegExp('/Importing.*u2-d1.log/', $commandTester->getDisplay());
        $this->assertRegExp('/Importing.*u1-d2.log/', $commandTester->getDisplay());
        $this->assertRegExp('/Importing.*u1-d1.log/', $commandTester->getDisplay());
    }

    public function testAlreadyImported()
    {
        $command = $this->getCommandMock();
        $container = $this->getContainerMock();
        $em = $this->getEmMock();
        $repository = $this->getLogRepositoryMock();

        // Mon, 23 Nov 2015 00:46:42 GMT + 1sec -- see root-access.log
        $repository->method('getLastStamp')->willReturn(1448239602 + 1);
        $em->method('getRepository')->willReturn($repository);
        $em->expects($this->exactly(0))->method('persist');
        $container->method('get')->willReturn($em);

        $command->method('getUserLogDir')->willReturn(__DIR__.'/../../DataFixtures/data');
        $command->setContainer($container);
        $commandTester = new CommandTester($command);
        $commandTester->execute(['username' => 'root']);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testGetUserLogDirException()
    {
        $method = self::getMethod('getUserLogDir');
        $command = new ImportLogsCommand();
        $method->invokeArgs($command, [uniqid()]);
    }

    public function testGetUserLogDir()
    {
        $method = self::getMethod('getUserLogDir');
        $command = new ImportLogsCommand();

        $dir = $method->invokeArgs($command, ['root']);
        $this->assertNotNull($dir);
    }

    /**
     * @param string $name Method name
     * @return \ReflectionMethod
     */
    protected static function getMethod($name) {
        $class = new \ReflectionClass('AppBundle\Command\ImportLogsCommand');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\AppBundle\Command\ImportLogsCommand
     */
    protected function getCommandMock()
    {
        return $this->getMockBuilder('AppBundle\Command\ImportLogsCommand')
            ->setConstructorArgs(['import-logs'])
            ->setMethods(['getUserLogDir'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\DependencyInjection\Container
     */
    protected function getContainerMock()
    {
        return $this->getMockBuilder('Symfony\Component\DependencyInjection\Container')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Doctrine\ORM\EntityManager
     */
    protected function getEmMock()
    {
        return $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(['persist', 'flush', 'clear', 'getRepository'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\AppBundle\Entity\LogRepository
     */
    protected function getLogRepositoryMock()
    {
        return $this->getMockBuilder('AppBundle\Entity\LogRepository')
            ->disableOriginalConstructor()
            ->setMethods(['getLastStamp'])
            ->getMock();
    }
}
