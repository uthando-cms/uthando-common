<?php

namespace UthandoCommonTest\Framework;

use UthandoCommonTest\Bootstrap;
use Zend\ServiceManager\ServiceManager;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
    }
}
