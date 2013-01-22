<?php

namespace Flint\Tests\Provider;

use Flint\Provider\FlintServiceProvider;
use Flint\Application;

class FlintServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->app = new Application(__DIR__, true);
    }

    public function testResolverIsOverridden()
    {
        $provider = new FlintServiceProvider;
        $provider->register($this->app);

        $this->assertInstanceOf('Flint\Controller\ControllerResolver', $this->app['resolver']);
    }

    public function testExceptionHandlerIsOverrriden()
    {
        $provider = new FlintServiceProvider;
        $provider->register($this->app);

        $this->assertInstanceOf('Symfony\Component\HttpKernel\EventListener\ExceptionListener', $this->app['exception_handler']);
    }

    public function testTwigFileLoaderFlintNamespacePathIsAdded()
    {
        $refl = new \ReflectionClass('Flint\Provider\FlintServiceProvider');
        $dir = dirname($refl->getFileName()) . '/../Resources/views';

        $loader = $this->getMockBuilder('Twig_Loader_Filesystem')->disableOriginalConstructor()->getMock();
        $loader->expects($this->once())->method('addPath')->with($this->equalTo($dir), $this->equalTo('Flint'));

        $this->app['twig.loader.filesystem'] = $this->app->share(function () use ($loader) {
            return $loader;
        });

        $provider = new FlintServiceProvider;
        $provider->boot($this->app);

        $this->app['twig.loader.filesystem'];
    }
}
