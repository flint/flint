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

    public function testUrlMatcherAndGeneratorIsAliasOfRouter()
    {
        $this->provider->register($this->app);

        $this->assertInstanceOf('Flint\Routing\ChainMatcher', $this->app['url_matcher']);
        $this->assertInstanceOf('Flint\Routing\ChainUrlGenerator', $this->app['url_generator']);
        $this->assertInstanceOf('Symfony\Component\Routing\Router', $this->app['router']);
    }

    public function testRedirectableUrlMatcherIsUsed()
    {
        $this->provider->register($this->app);

        $this->assertEquals('Silex\\RedirectableUrlMatcher', $this->app['router']->getOption('matcher_class'));
        $this->assertEquals('Silex\\RedirectableUrlMatcher', $this->app['router']->getOption('matcher_base_class'));
    }
}
