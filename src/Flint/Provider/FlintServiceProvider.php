<?php

namespace Flint\Provider;

use Flint\Listener\ExceptionListener;
use Flint\Controller\ControllerResolver;
use Silex\Application;

/**
 * @package Flint
 */
class FlintServiceProvider implements \Silex\ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Application $app)
    {
        $app['resolver'] = $app->share($app->extend('resolver', function ($resolver, $app) {
            return new ControllerResolver($resolver, $app);
        }));

        $app['exception_handler'] = $app->share($app->extend('exception_handler', function ($handler, $app) {
            return new ExceptionListener('Flint\\Controller\\ExceptionController::showAction', $handler);
        }));
    }

    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {
    }
}
