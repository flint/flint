<?php

namespace Flint\Tests\Provider;

use Flint\Provider\RoutingServiceProvider;
use Flint\Application;

class RoutingServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->app = new Application(__DIR__, true);
        $this->provider = new RoutingServiceProvider;
    }

    public function testUrlMatcherIsAliasOrRouter()
    {
        $this->provider->register($this->app);

        $this->assertInstanceOf('Symfony\Component\Routing\Router', $this->app['url_matcher']);
    }

    public function testRouteCollectionIsGottenFromRouter()
    {
        $router = $this->getMockBuilder('Symfony\Component\Routing\Router')->disableOriginalConstructor()->getMock();
        $router->expects($this->once())->method('getRouteCollection');

        $this->provider->register($this->app);

        $this->app['router'] = $router;

        $this->app['routes'];
    }
}
