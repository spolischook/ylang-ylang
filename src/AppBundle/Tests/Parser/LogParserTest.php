<?php

namespace AppBundle\Tests\Parser;

use AppBundle\Parser\LogParser;

class LogParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new LogParser();
        $entity = $parser->parseLog('127.0.0.1 - - [20/Nov/2015:16:22:39 +0200] "POST /login_check HTTP/1.1" 302 681');

        $this->assertInstanceOf('AppBundle\Entity\Log', $entity);
        $this->assertEquals('127.0.0.1', $entity->getHost());
        $this->assertEquals('-', $entity->getLogname());
        $this->assertEquals('-', $entity->getUser());
        $this->assertEquals(strtotime('20/Nov/2015:16:22:39 +0200'), $entity->getStamp());
        $this->assertEquals('20/Nov/2015:16:22:39 +0200', $entity->getTime());
        $this->assertEquals('POST /login_check HTTP/1.1', $entity->getRequest());
        $this->assertEquals('302', $entity->getStatus());
        $this->assertEquals('681', $entity->getResponseBytes());
    }

    /**
     * @expectedException \Kassner\LogParser\FormatException
     */
    public function testWrongFormat()
    {
        $parser = new LogParser();
        $entity = $parser->parseLog('Wrong format');
    }

    public function testParseFile()
    {
        $parser = new LogParser();
        $this->assertCount(3, $parser->parseFile(realpath(__DIR__.'/test.log'), 'test'));
        $this->assertCount(2, $parser->parseFile(realpath(__DIR__.'/test.log'), 'test', 1448118000));
    }
}
