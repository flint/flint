<?php

namespace Flint;

use Flint\Provider\FlintServiceProvider;
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
    }
}
