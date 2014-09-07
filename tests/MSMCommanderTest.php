<?php

namespace F481\MSM_Frontend\Test;

use F481\MSM_Frontend\MSMCommander;


class MSMCommanderTest extends \PHPUnit_Framework_TestCase {

    protected $commander;

    protected function setUp()
    {
        $this->commander = new MSMCommander();
        $this->testMSMCommandAvailable();
    }

    public function testMSMCommandAvailable()
    {
        if ($this->commander->isMSMAvailable() == false) {
            $this->fail('msm command not found');
        }
    }

    public function testGetServersWithEmptyServerList()
    {
        $this->assertNull($this->commander->getServers());
    }

} 