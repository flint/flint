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

    public function testResolverIsOverridden()
    {
        $this->provider->register($this->app);

        $this->assertInstanceOf('Flint\Controller\ControllerResolver', $this->app['resolver']);
    }

    public function testMatcherIsChain()
    {
        $this->provider->register($this->app);

        $this->assertInstanceOf('Flint\Routing\Matcher\ChainUrlMatcher', $this->app['url_matcher']);
    }

    public function testGeneratorIsAliasOfRouter()
    {
        $this->provider->register($this->app);

        $this->assertInstanceOf('Symfony\Component\Routing\Router', $this->app['router']);

        $this->assertSame($this->app['router'], $this->app['url_generator']);
    }

    public function testRedirectableUrlMatcherIsUsed()
    {
        $this->provider->register($this->app);

        $this->assertEquals('Silex\\RedirectableUrlMatcher', $this->app['router']->getOption('matcher_class'));
        $this->assertEquals('Silex\\RedirectableUrlMatcher', $this->app['router']->getOption('matcher_base_class'));
    }
}
