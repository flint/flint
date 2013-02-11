<?php

namespace Flint\Tests;

class ApplicationAwareTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementesInterface()
    {
        $aware = $this->getMockForAbstractClass('Flint\ApplicationAware');

        $this->assertInstanceOf('Flint\ApplicationAwareInterface', $aware);
    }

    public function testAppPropertyIsSet()
    {
        $refl = new \ReflectionProperty('Flint\ApplicationAware', 'app');
        $refl->setAccessible(true);

        $mock = $this->getMockForAbstractClass('Flint\ApplicationAware');

        $this->assertInternalType('null', $refl->getValue($mock));

        $app = $this->getMockBuilder('Flint\Application')->disableOriginalConstructor()->getMock();

        $mock->setApplication($app);

        $this->assertEquals($app, $refl->getValue($mock));
    }
}
