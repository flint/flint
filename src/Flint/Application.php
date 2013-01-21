<?php

namespace Flint;

use Flint\Provider\ConfigServiceProvider;
use Flint\Provider\FlintServiceProvider;
use Flint\Provider\RoutingServiceProvider;
use Silex\Provider\TwigServiceProvider;

/**
 * @package Flint
 */
class Application extends \Silex\Application
{
    /**
     * Assigns rootDir and debug to the pimple container. Also replaces the
     * normal resolver with a ApplicationAware Resolver.
     *
     * @param string $rootDir
     * @param boolean $debug
     */
    public function __construct($rootDir, $debug = false)
    {
        parent::__construct(array(
            'root_dir' => $rootDir,
            'debug' => $debug,
        ));

        $this->register(new TwigServiceProvider);
        $this->register(new FlintServiceProvider);
        $this->register(new ConfigServiceProvider);
        $this->register(new RoutingServiceProvider);
    }

    /**
     * Loads routing.xml from config directory.
     * 
     * @param string $prefix
     */
    public function flush($prefix = '')
    {
        parent::flush($prefix);

        $collection = $this['routing.loader']->load('routing.xml');

        $this['routes']->addCollection($collection);
    }
}
