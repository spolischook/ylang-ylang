<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\ImportLogsCommand;

class ImportLogsCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFiles()
    {
        $method = self::getMethod('getFiles');
        $command = new ImportLogsCommand();
        $files = $method->invokeArgs($command, [realpath(__DIR__.'/../..')]);

        $this->assertContains(realpath(__DIR__.'/ImportLogsCommandTest.php'), $files);
        $this->assertContains(realpath(__DIR__.'/../../AppBundle.php'), $files);
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
}
