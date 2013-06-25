<?php

namespace Flint\Tests\Config;

use Flint\Config\Configurator;
use Pimple;

class ConfiguratorTest extends \PHPUnit_Framework_TestCase
{
    const CACHE_CONTENT = <<<CONTENT
<?php \$parameters = array (
  'service_parameter' => 'hello',
);
CONTENT;

    public function setUp()
    {
        $this->loader = $this->getMock('Symfony\Component\Config\Loader\LoaderInterface');
        $this->cacheFile = "/var/tmp/1058386122.php";
    }

    public function tearDown()
    {
        @unlink($this->cacheFile);
    }

    public function testItBuilderPimple()
    {
        $this->loader->expects($this->once())->method('load')->with($this->equalTo('config.json'))
            ->will($this->returnValue(array('service_parameter' => 'hello')));

        $pimple = new Pimple;

        $this->createConfigurator()->configure($pimple, 'config.json');

        $this->assertEquals('hello', $pimple['service_parameter']);
    }

    public function testItBuildsPimpleWithInheritedConfig()
    {
        $this->loader->expects($this->at(0))->method('load')->with($this->equalTo('config.json'))
            ->will($this->returnValue(array('@import' => 'inherited.json', 'service_parameter' => 'hello')));

        $this->loader->expects($this->at(1))->method('load')->with($this->equalTo('inherited.json'))
            ->will($this->returnValue(array('@import' => 'most_parent.json', 'service_parameter' => 'not hello', 'new_parameter' => false)));

        $this->loader->expects($this->at(2))->method('load')->with($this->equalTo('most_parent.json'))
            ->will($this->returnValue(array('service_parameter' => 'other thing', 'new_parameter' => true)));

        $pimple = new Pimple;

        $this->createConfigurator()->configure($pimple, 'config.json');

        $this->assertEquals('hello', $pimple['service_parameter']);
        $this->assertFalse($pimple['new_parameter']);
    }

    public function testAFreshCacheSkipsLoader()
    {
        // Create a fresh cache
        file_put_contents($this->cacheFile, static::CACHE_CONTENT);

        $pimple = new Pimple;

        $this->loader->expects($this->never())->method('load');

        $this->createConfigurator(false)->configure($pimple, 'config.json');

        $this->assertEquals('hello', $pimple['service_parameter']);
    }

    public function testStaleCacheWritesFile()
    {
        $this->loader->expects($this->once())->method('load')->with($this->equalTo('config.json'))->will($this->returnValue(array(
            'service_parameter' => 'hello',
        )));

        $pimple = new Pimple;

        $this->createConfigurator(false)->configure($pimple, 'config.json');

        $this->assertEquals(file_get_contents($this->cacheFile), static::CACHE_CONTENT);

    }

    protected function createConfigurator($debug = true)
    {
        return new Configurator($this->loader, '/var/tmp', $debug);
    }
}
