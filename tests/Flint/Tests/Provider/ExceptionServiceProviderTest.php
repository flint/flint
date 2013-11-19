<?php

namespace Flint\Tests\Provider;

use Flint\Provider\ExceptionServiceProvider;
use Flint\Application;

class ExceptionServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->app = new Application(__DIR__, true);
        $this->provider = new ExceptionServiceProvider;
    }

    public function testCustomExceptionController()
    {
        $this->provider->register($this->app);
        $this->app['exception_controller'] = 'Acme\\Controller\\ExceptionController::showAction';

        $listener = $this->app['exception_handler'];
        $refl = new \ReflectionProperty($listener, 'controller');
        $refl->setAccessible(true);

        $this->assertEquals('Acme\\Controller\\ExceptionController::showAction', $refl->getValue($listener));
    }

    public function testExceptionHandlerIsOverrriden()
    {
        $this->provider->register($this->app);

        $this->assertInstanceOf('Symfony\Component\HttpKernel\EventListener\ExceptionListener', $this->app['exception_handler']);
    }

    public function testTwigFileLoaderFlintNamespacePathIsAdded()
    {
        $refl = new \ReflectionClass('Flint\Provider\ExceptionServiceProvider');
        $dir = dirname($refl->getFileName()) . '/../Resources/views';

        $loader = $this->getMockBuilder('Twig_Loader_Filesystem')->disableOriginalConstructor()->getMock();
        $loader->expects($this->once())->method('addPath')->with($this->equalTo($dir), $this->equalTo('Flint'));

        $this->app['twig.loader.filesystem'] = $this->app->share(function () use ($loader) {
            return $loader;
        });

        $this->provider->register($this->app);

        $this->app['twig.loader.filesystem'];
    }
}
