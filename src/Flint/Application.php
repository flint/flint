<?php

namespace Flint;

use Flint\Controller\ControllerResolver;

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
        parent::__construct();

        $this['root_dir'] = $rootDir;
        $this['debug'] = $debug;

        $this['resolver'] = $this->share($this->extend('resolver', function ($resolver, $app) {
            return new ControllerResolver($resolver, $app);
        }));
    }
}
