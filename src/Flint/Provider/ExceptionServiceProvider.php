<?php

namespace Flint\Provider;

use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Flint\Controller\ControllerResolver;
use Pimple;

/**
 * @package Flint
 */
class ExceptionServiceProvider implements \Silex\Api\ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Pimple $app)
    {
        $app['exception_controller'] = 'Flint\\Controller\\ExceptionController::showAction';

        $app['exception_handler'] = function ($app) {
            return new ExceptionListener($app['exception_controller'], $app['logger']);
        };

        $app['twig.loader.filesystem'] = $app->extend('twig.loader.filesystem', function ($loader) {
            $loader->addPath(__DIR__ . '/../Resources/views', 'Flint');

            return $loader;
        });
    }
}
