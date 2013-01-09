<?php

namespace Flint\Provider;

use Flint\Controller\ControllerResolver;
use Silex\Application;

/**
 * @package Flint
 */
class ControllerServiceProvider implements \Silex\ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Application $app)
    {
        $app['resolver'] = $app->share($app->extend('resolver', function ($resolver, $app) {
            return new ControllerResolver($resolver, $app);
        }));
    }

    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {
    }
}
