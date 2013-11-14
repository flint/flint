<?php

namespace Flint\Tests;

use Flint\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->app = new Application(__DIR__, true);
    }

    public function testParametersCanBeSetFromConstructor()
    {
        $app = new Application(__DIR__, true, array(
            'my_parameter' => 'my_parameter_value',
        ));

        $this->assertEquals('my_parameter_value', $app['my_parameter']);
    }

    public function testRootDirAndDebugIsSet()
    {
        $app = new Application('/my/root_dir', true);

        $this->assertTrue($app['debug']);
        $this->assertEquals('/my/root_dir', $app['root_dir']);
    }

    public function testConfigureWillLoadConfigFile()
    {
        $app = new Application('/my/root_dir', true);
        $app['configurator'] = $this->getMockBuilder('Flint\Config\Configurator')
            ->disableOriginalConstructor()->getMock();

        $app['configurator']->expects($this->once())->method('configure')
            ->with($this->equalTo($app), $this->equalTo('config.json'));

        $app->configure('config.json');
    }
}
