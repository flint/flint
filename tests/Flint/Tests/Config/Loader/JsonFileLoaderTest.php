<?php

namespace Flint\Tests\Config\Loader;

use Flint\Config\Loader\JsonFileLoader;
use Symfony\Component\Config\FileLocator;

class JsonFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $paths = array(__DIR__ . '/../../Fixtures');

        $normalizer = $this->getMock('Flint\Config\Normalizer\NormalizerInterface');
        $normalizer->expects($this->any())->method('normalize')->will($this->returnCallback(function ($args) {
            return $args;
        }));

        $this->loader = new JsonFileLoader($normalizer, new FileLocator($paths));
    }

    public function testItLoadsAsJsonFile()
    {
        $this->assertEquals(array('service_parameter' => 'hello'), $this->loader->load('config.json'));
    }

    public function testItSupportsJson()
    {
        $this->assertTrue($this->loader->supports('config.json'));
        $this->assertFalse($this->loader->supports('config.ini'));
        $this->assertFalse($this->loader->supports('config.xml'));
        $this->assertFalse($this->loader->supports('config.php'));
    }

    public function testUnsupportedThrowExceptionWhenLoading()
    {
        $this->setExpectedException('Symfony\Component\Config\Exception\FileLoaderLoadException');

        $this->loader->load('unsupported.ini');
    }
}
