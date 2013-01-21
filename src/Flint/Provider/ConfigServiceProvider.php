<?php

namespace Flint\Provider;

use Silex\Application;
use Symfony\Component\Config\FileLocator;

/**
 * @package Flint
 */
class ConfigServiceProvider implements \Silex\ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Application $app)
    {
        $app['config.locator'] = $app->share(function (Application $app) {
            return new FileLocator($app['root_dir'] . '/config');
        });
    }

    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {
    }
}
