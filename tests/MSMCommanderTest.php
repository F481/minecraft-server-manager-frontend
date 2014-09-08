<?php

namespace F481\MSM_Frontend\Test;

use F481\MSM_Frontend\MSMCommander;


class MSMCommanderTest extends \PHPUnit_Framework_TestCase {

    protected $commander;

    protected function setUp()
    {
        $this->commander = new MSMCommander();
    }


    public function testMSMCommandAvailable()
    {
        if ($this->commander->isMSMAvailable() == false) {
            $this->fail('msm command not found');
        }
        $this->assertTrue($this->commander->isMSMAvailable());
    }

    public function testGetServersWithEmptyServerList()
    {
        $this->assertNull($this->commander->getServers());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage There is no server with the name "yzhxbj9531"
     */
    public function testIsRunningWithNonExistingServer()
    {
        $this->commander->isRunning('yzhxbj9531');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage There is no server with the name "yzhxbj9531"
     */
    public function testGetPlayersWithNonExistingServer()
    {
        $this->commander->getPlayers('yzhxbj9531');
    }

    public function testCreateServer()
    {
        $this->assertFalse($this->commander->createServer('name with spaces'));

        $this->assertTrue($this->commander->createServer('serverstart'));
        $this->assertTrue($this->commander->createServer('CapitalLetters'));
        $this->assertTrue($this->commander->createServer('0987654321'));
        $this->assertTrue($this->commander->createServer('name-with-dashes'));
        $this->assertTrue($this->commander->createServer('name_with_underscores'));
        $this->assertTrue($this->commander->createServer('Combination-of_different1Things2'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage A server with name "Duplicate" already exists
     */
    public function testCreateServerDuplicate()
    {
        $this->assertTrue($this->commander->createServer('Duplicate'));
        $this->commander->createServer('Duplicate');
    }

    public function testGetPlayers()
    {
        $this->markTestSkipped();
    }

} 