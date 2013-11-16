<?php

namespace Flint;

use Flint\Provider\TackerServiceProvider;
use Flint\Provider\ExceptionServiceProvider;
use Flint\Provider\RoutingServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\Config\FileLocator;

/**
 * @package Flint
 */
class Application extends \Silex\Application
{
    /**
     * Assigns rootDir and debug to the pimple container. Also replaces the
     * normal resolver with a PimpleAware Resolver.
     *
     * @param string  $rootDir
     * @param boolean $debug
     * @param array   $parameters
     */
    public function __construct($rootDir, $debug = false, array $parameters = array())
    {
        parent::__construct($parameters);

        $this['root_dir'] = $rootDir;
        $this['debug'] = $debug;
        $this['paths'] = array($rootDir);
        $this['locator'] = function ($app) {
            return new FileLocator($app['paths']);
        };

        $this->register(new TackerServiceProvider);
        $this->register(new RoutingServiceProvider);
        $this->register(new TwigServiceProvider);
        $this->register(new ExceptionServiceProvider);
    }

    /**
     * @see Tacker\Configurator::configure()
     * @param string $resource
     */
    public function configure($resource)
    {
        $this['tacker']->configure($this, $resource);
    }
}
