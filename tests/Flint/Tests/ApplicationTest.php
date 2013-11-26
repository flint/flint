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

    /**
     * @dataProvider configFilesProvider
     */
    public function testConfigureWillLoadConfigFile($file)
    {
        $app = new Application(__DIR__ . '/Fixtures', true);
        $app->configure($file);

        $this->assertEquals('world', $app['hello']);
    }

    /**
     * @dataProvider serviceProvidersProvider
     */
    public function testProvidersAreRegistered($index, $providerClassName)
    {
        $mock = $this->getMockBuilder('Flint\Application')
            ->setMethods(array('register'))
            ->disableOriginalConstructor()->getMock();

        $mock->expects($this->at($index))
            ->method('register')
            ->with($this->isInstanceOf($providerClassName));

        call_user_func_array(array($mock, '__construct'), array(__DIR__, true));
    }

    public function configFilesProvider()
    {
        return array(
            array('config.json'),
            array('config.php'),
            array('config.ini'),
            array('config.yml'),
        );
    }

    public function serviceProvidersProvider()
    {
        return array(
            array(0, 'Flint\Provider\ConfigServiceProvider'),
            array(1, 'Flint\Provider\RoutingServiceProvider'),
            array(2, 'Silex\Provider\TwigServiceProvider'),
            array(3, 'Flint\Provider\FlintServiceProvider'),
        );
    }
}
