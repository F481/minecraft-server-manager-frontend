<?php

namespace F481\MSM_Frontend\Test;

use F481\MSM_Frontend\MSMCommander;
use Symfony\Component\Yaml\Exception\RuntimeException;


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

} 