<?php

namespace Flint\Tests;

class PimpleAwareTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementesInterface()
    {
        $aware = $this->getMockForAbstractClass('Flint\PimpleAware');

        $this->assertInstanceOf('Flint\PimpleAwareInterface', $aware);
    }

    public function testAppPropertyIsSet()
    {
        $refl = new \ReflectionProperty('Flint\PimpleAware', 'pimple');
        $refl->setAccessible(true);

        $mock = $this->getMockForAbstractClass('Flint\PimpleAware');

        $this->assertInternalType('null', $refl->getValue($mock));

        $pimple = $this->getMockBuilder('Flint\Application')->disableOriginalConstructor()->getMock();

        $mock->setPimple($pimple);

        $this->assertEquals($pimple, $refl->getValue($mock));
    }
}
